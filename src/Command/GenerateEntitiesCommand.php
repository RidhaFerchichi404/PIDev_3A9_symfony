<?php

namespace App\Command;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Column;
use Doctrine\DBAL\Schema\Table;
use Symfony\Component\Console\Attribute\AsCommand;
use Doctrine\DBAL\Schema\AbstractSchemaManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;

#[AsCommand(
    name: 'app:generate:entities',
    description: 'Automatically generates entity classes from the database schema',
)]
class GenerateEntitiesCommand extends Command
{
    private Connection $connection;
    private ?AbstractSchemaManager $schemaManager = null;
    private array $generatedRelations = [];

    public function __construct(Connection $connection, Filesystem $filesystem)
    {
        parent::__construct();
        $this->connection = $connection;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title("Generating Entity Classes from Database...");

        try {
            $schemaManager = $this->getSchemaManager();
            $tables = $schemaManager->listTables();
            
            // Generate Salledesport first
            $salleTable = $this->findTableByName($tables, 'salledesport');
            if ($salleTable) {
                $this->generateSalleDesportEntity($salleTable);
                $io->success("Generated: src/Entity/Salledesport.php");
            }

            // Then generate other entities
            $tablesToGenerate = ['abonnement', 'promotion'];
            $oneToManyRelations = [];
            $manyToOneRelationsName = [];
            $oneToManyRelationsName = [];

            foreach ($tables as $table) {
                if (in_array($table->getName(), $tablesToGenerate)) {
                    $this->generateEntity($table, $oneToManyRelations, $manyToOneRelationsName, $oneToManyRelationsName);
                    $io->success("Generated: src/Entity/" . ucfirst($table->getName()) . ".php");
                }
            }

        } catch (\Exception $e) {
            $io->error("Failed to retrieve database schema: " . $e->getMessage());
            return Command::FAILURE;
        }

        $io->success("All entities successfully generated in src/Entity/");
        return Command::SUCCESS;
    }

    private function findTableByName(array $tables, string $name): ?Table
    {
        foreach ($tables as $table) {
            if ($table->getName() === $name) {
                return $table;
            }
        }
        return null;
    }

    private function generateSalleDesportEntity(Table $table): void
{
    // Initialisez les tableaux qui seront passés par référence
    $oneToManyRelations = [];
    $manyToOneRelationsName = [];
    $oneToManyRelationsName = [];
    
    $entityCode = "<?php\n\nnamespace App\\Entity;\n\n";
    $entityCode .= "use Doctrine\\ORM\\Mapping as ORM;\n";
    $entityCode .= "use Doctrine\\Common\\Collections\\Collection;\n";
    $entityCode .= "use Doctrine\\Common\\Collections\\ArrayCollection;\n";
    $entityCode .= "use App\\Entity\\Abonnement;\n\n";
    
    $entityCode .= "#[ORM\\Entity]\n";
    $entityCode .= "#[ORM\\Table(name: 'salledesport')]\n";
    $entityCode .= "class Salledesport\n{\n";

    // Ajoutez toutes les colonnes de la table
    foreach ($table->getColumns() as $column) {
        $entityCode .= $this->generateProperty(
            $column, 
            $table->getPrimaryKey()?->getColumns() ?? [], 
            [], 
            'Salledesport', 
            $oneToManyRelations,
            $manyToOneRelationsName,
            $oneToManyRelationsName
        );
    }

    // Ajoutez la relation OneToMany
    $entityCode .= "\n    #[ORM\\OneToMany(mappedBy: 'salle', targetEntity: Abonnement::class)]\n";
    $entityCode .= "    private Collection \$abonnements;\n\n";

    // Ajoutez le constructeur
    $entityCode .= "    public function __construct()\n    {\n";
    $entityCode .= "        \$this->abonnements = new ArrayCollection();\n";
    $entityCode .= "    }\n";

    // Ajoutez les getters et setters
    foreach ($table->getColumns() as $column) {
        $entityCode .= $this->generateGettersAndSetters($column);
    }

    // Ajoutez les méthodes pour la relation
    $entityCode .= $this->generateAbonnementsMethods();

    $entityCode .= "}\n";

    file_put_contents(__DIR__.'/../../src/Entity/Salledesport.php', $entityCode);
}
    

    private function getSchemaManager(): AbstractSchemaManager
    {
        if ($this->schemaManager === null) {
            $this->schemaManager = $this->connection->createSchemaManager();
        }
        return $this->schemaManager;
    }

    private function generateEntity(Table $table, array &$oneToManyRelations, array &$manyToOneRelationsName, array &$oneToManyRelationsName): void
    {
        $className = ucfirst($table->getName());
        $entityCode = "<?php\n\nnamespace App\\Entity;\n\nuse Doctrine\\ORM\\Mapping as ORM;\n";
        
        // Add specific imports
        if ($className === 'Promotion') {
            $entityCode .= "use App\\Entity\\Abonnement;\n";
        } elseif ($className === 'Abonnement') {
            $entityCode .= "use App\\Entity\\Salledesport;\n";
            $entityCode .= "use Doctrine\\Common\\Collections\\Collection;\n";
            $entityCode .= "use Doctrine\\Common\\Collections\\ArrayCollection;\n";
        }
        
        $entityCode .= "\n#[ORM\\Entity]\n";
        $entityCode .= "#[ORM\\Table(name: '".$table->getName()."')]\n";
        $entityCode .= "class $className\n{\n";

        $primaryKeys = $table->getPrimaryKey()?->getColumns() ?? [];
        $foreignKeys = $this->getForeignKeys([$table->getName()]);

        foreach ($table->getColumns() as $column) {
            $entityCode .= $this->generateProperty($column, $primaryKeys, $foreignKeys, $className, $oneToManyRelations, $manyToOneRelationsName, $oneToManyRelationsName);
        }

        // Add constructor for Abonnement
        if ($className === 'Abonnement') {
            $entityCode .= "\n    public function __construct()\n    {\n";
            $entityCode .= "        \$this->promotions = new ArrayCollection();\n";
            $entityCode .= "    }\n";
        }

        // Add relation methods
        if (isset($oneToManyRelations[$className])) {
            foreach ($oneToManyRelations[$className] as $relation) {
                $entityCode .= $relation;
            }
        }

        // Add specific methods
        if ($className === 'Abonnement') {
            $entityCode .= $this->generatePromotionMethods();
            $entityCode .= $this->generateSalleMethods();
        } elseif ($className === 'Promotion') {
            $entityCode .= $this->generateAbonnementMethods();
        }

        // Generate getters and setters
        foreach ($table->getColumns() as $column) {
            if (!in_array($column->getName(), ['SalleID', 'AbonnementID'])) {
                $entityCode .= $this->generateGettersAndSetters($column);
            }
        }

        $entityCode .= "}\n";

        // Save the entity file
        $filePath = __DIR__ . "/../../src/Entity/$className.php";
        file_put_contents($filePath, $entityCode);
    }

    private function generateProperty(Column $column, array $primaryKeys, array $foreignKeys, string $className, array &$oneToManyRelations, array &$manyToOneRelationsName, array &$oneToManyRelationsName): string
    {
        $columnName = $column->getName();
        $propertyCode = '';
        
        // Handle special relations
        if ($className === 'Abonnement' && $columnName === 'SalleID') {
            $propertyCode .= "    #[ORM\\ManyToOne(targetEntity: Salledesport::class, inversedBy: 'abonnements')]\n";
            $propertyCode .= "    #[ORM\\JoinColumn(name: 'SalleID', referencedColumnName: 'SalleID')]\n";
            $propertyCode .= "    private ?Salledesport \$salle = null;\n\n";
            
            $oneToManyRelations['Salledesport'][] = 
                "    #[ORM\\OneToMany(mappedBy: \"salle\", targetEntity: Abonnement::class)]\n" .
                "    private Collection \$abonnements;\n";
                
            return $propertyCode;
        }
        
        if ($className === 'Promotion' && $columnName === 'AbonnementID') {
            $propertyCode .= "    #[ORM\\ManyToOne(targetEntity: Abonnement::class, inversedBy: 'promotions')]\n";
            $propertyCode .= "    #[ORM\\JoinColumn(name: 'AbonnementID', referencedColumnName: 'AbonnementID')]\n";
            $propertyCode .= "    private ?Abonnement \$abonnement = null;\n\n";
            
            $oneToManyRelations['Abonnement'][] = 
                "    #[ORM\\OneToMany(mappedBy: \"abonnement\", targetEntity: Promotion::class)]\n" .
                "    private Collection \$promotions;\n";
                
            return $propertyCode;
        }

        // Handle regular columns
        $typeClass = get_class($column->getType());
        $length = $column->getLength();
        $isPrimaryKey = in_array($columnName, $primaryKeys);

        $doctrineType = match ($typeClass) {
            'Doctrine\DBAL\Types\IntegerType' => 'integer',
            'Doctrine\DBAL\Types\BigIntType' => 'bigint',
            'Doctrine\DBAL\Types\DecimalType' => 'decimal',
            'Doctrine\DBAL\Types\FloatType', 'Doctrine\DBAL\Types\DoubleType' => 'float',
            'Doctrine\DBAL\Types\BooleanType' => 'boolean',
            'Doctrine\DBAL\Types\DateTimeType', 'Doctrine\DBAL\Types\TimestampType' => 'datetime',
            'Doctrine\DBAL\Types\DateType' => 'date',
            'Doctrine\DBAL\Types\TextType' => 'text',
            'Doctrine\DBAL\Types\StringType', 'Doctrine\DBAL\Types\VarCharType' => 'string',
            default => 'string',
        };

        $lengthAnnotation = ($doctrineType === 'string' && $length) ? ", length: $length" : "";
        $precisionAnnotation = ($doctrineType === 'decimal') ? ", precision: 10, scale: 2" : "";

        $propertyCode .= "\n    " . ($isPrimaryKey ? "#[ORM\\Id]\n    " : "");
        $propertyCode .= "#[ORM\\Column(type: \"$doctrineType\"$lengthAnnotation$precisionAnnotation)]\n";
        $propertyCode .= "    private " . $this->getPHPTypeFromDoctrine($doctrineType) . " \$$columnName;\n";

        return $propertyCode;
    }

    private function generateAbonnementsMethods(): string
    {
        return "
    /**
     * @return Collection<int, Abonnement>
     */
    public function getAbonnements(): Collection
    {
        return \$this->abonnements;
    }

    public function addAbonnement(Abonnement \$abonnement): static
    {
        if (!\$this->abonnements->contains(\$abonnement)) {
            \$this->abonnements->add(\$abonnement);
            \$abonnement->setSalle(\$this);
        }

        return \$this;
    }

    public function removeAbonnement(Abonnement \$abonnement): static
    {
        if (\$this->abonnements->removeElement(\$abonnement)) {
            if (\$abonnement->getSalle() === \$this) {
                \$abonnement->setSalle(null);
            }
        }

        return \$this;
    }";
    }

    private function generatePromotionMethods(): string
    {
        return "
    /**
     * @return Collection<int, Promotion>
     */
    public function getPromotions(): Collection
    {
        return \$this->promotions;
    }

    public function addPromotion(Promotion \$promotion): static
    {
        if (!\$this->promotions->contains(\$promotion)) {
            \$this->promotions->add(\$promotion);
            \$promotion->setAbonnement(\$this);
        }

        return \$this;
    }

    public function removePromotion(Promotion \$promotion): static
    {
        if (\$this->promotions->removeElement(\$promotion)) {
            if (\$promotion->getAbonnement() === \$this) {
                \$promotion->setAbonnement(null);
            }
        }

        return \$this;
    }";
    }

    private function generateSalleMethods(): string
    {
        return "
    public function getSalle(): ?Salledesport
    {
        return \$this->salle;
    }

    public function setSalle(?Salledesport \$salle): static
    {
        \$this->salle = \$salle;

        return \$this;
    }";
    }

    private function generateAbonnementMethods(): string
    {
        return "
    public function getAbonnement(): ?Abonnement
    {
        return \$this->abonnement;
    }

    public function setAbonnement(?Abonnement \$abonnement): static
    {
        \$this->abonnement = \$abonnement;

        return \$this;
    }";
    }

    private function generateGettersAndSetters(Column $column): string
    {
        $columnName = $column->getName();
        $methodName = ucfirst($columnName);
        $type = $this->getPHPTypeFromDoctrine(get_class($column->getType()));

        return "
    public function get$methodName(): $type
    {
        return \$this->$columnName;
    }

    public function set$methodName($type \$$columnName): static
    {
        \$this->$columnName = \$$columnName;
        return \$this;
    }\n";
    }

    private function getPHPTypeFromDoctrine(string $doctrineType): string
    {
        $mapping = [
            'integer' => 'int',
            'smallint' => 'int',
            'bigint' => 'int',
            'string' => 'string',
            'text' => 'string',
            'boolean' => 'bool',
            'decimal' => 'float',
            'float' => 'float',
            'date' => '\DateTimeInterface',
            'datetime' => '\DateTimeInterface',
            'datetimetz' => '\DateTimeInterface',
            'time' => '\DateTimeInterface',
        ];

        return $mapping[$doctrineType] ?? 'mixed';
    }

    public function getForeignKeys(array $tables): array
    {
        $foreignKeys = [];
        $schemaManager = $this->connection->createSchemaManager();

        foreach ($tables as $tableName) {
            $sql = "
            SELECT 
                COLUMN_NAME, 
                REFERENCED_TABLE_NAME, 
                REFERENCED_COLUMN_NAME
            FROM 
                INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
            WHERE 
                TABLE_NAME = :tableName AND 
                REFERENCED_TABLE_NAME IS NOT NULL
            ";

            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue(':tableName', $tableName);
            $fks = $stmt->executeQuery()->fetchAllAssociative();

            foreach ($fks as $fk) {
                $foreignKeys[$fk['COLUMN_NAME']] = [
                    'referencedTable' => $fk['REFERENCED_TABLE_NAME'],
                    'referencedColumn' => $fk['REFERENCED_COLUMN_NAME']
                ];
            }
        }

        return $foreignKeys;
    }
}