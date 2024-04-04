<?php

/**
 * Create a new model class. The name of the model must have a corresponding table
 * in the database. The column names will be used to create properties.
 * 
 * Arguments: [model name]
 * 
 * @todo Make this so it can update the model when the table is updated.
 * 
 */
require_once "../../Code/autoloader.php";

$db = new \Database\Connection();

if (!isset($argv[1])) {
    echo "No model name provided \n";
    die();
}

/**
 * @todo Improve data validation
 */
$tableName = $argv[1];
$newFile   = "/var/www/framework/Code/Models/" . $tableName . ".php";

/**
 * Check for existing model class to avoid overwriting custom methods.
 */
if (file_exists($newFile)) {
    echo "\nA model class file for this table already exists!!\nUpdate the file with current information or delete it to continue.\nCustom methods will be overwritten if this script runs.\n\n";
    die();
}

$sqlTableData = "select column_name from information_schema.columns where table_schema = 'framework' and table_name = ?";

$stmt = $db->prepare($sqlTableData);
$stmt->execute([$tableName]);

// CHECK FOR EXISTING TABLE...
if ($stmt->rowCount()) {
    $result = $stmt->fetchAll();

    $fileContent  = "";
    $newModelFile = fopen($newFile, "w");
    $fileContent  = "<?php" . PHP_EOL . PHP_EOL;
    $fileContent  .= "namespace Models\\$tableName;" . PHP_EOL . PHP_EOL;
    $fileContent  .= "class $tableName extends \Models\ParentModel {" . PHP_EOL . PHP_EOL;

    foreach ($result as $column) {
        $fileContent .= "    public $" . $column['COLUMN_NAME'] . ";" . PHP_EOL;
    }

    $fileContent .= PHP_EOL;
    $fileContent .= "    protected \$db;" . PHP_EOL . PHP_EOL;
    $fileContent .= "    public function __construct(\$id = FALSE) {" . PHP_EOL;
    $fileContent .= "        global \$db;" . PHP_EOL;
    $fileContent .= "        \$this->db = \$db;" . PHP_EOL . PHP_EOL;
    $fileContent .= "        if (\$id) {" . PHP_EOL;
    $fileContent .= "            \$sql = \$this->db->prepare(\"select * from $tableName where id = ?\");" . PHP_EOL;
    $fileContent .= "            \$sql->execute([\$id]);" . PHP_EOL;
    $fileContent .= "            \$row = \$sql->fetch(\PDO::FETCH_ASSOC);" . PHP_EOL;
    $fileContent .= "            foreach (\$row as \$col => \$val) {" . PHP_EOL;
    $fileContent .= "                \$this->\$col = \$val;" . PHP_EOL;
    $fileContent .= "            }" . PHP_EOL;
    $fileContent .= "        }" . PHP_EOL;
    $fileContent .= "    }" . PHP_EOL . PHP_EOL;

    $fileContent .= "    public function saveNew() {" . PHP_EOL;
    $fileContent .= "        try {" . PHP_EOL;
    $fileContent .= "            \$this->id = parent::saveNew();" . PHP_EOL;
    $fileContent .= "        } catch (\Exception \$ex) {" . PHP_EOL;
    $fileContent .= "            error_log('ERROR: ' . __CLASS__ . '->saveNew: ' . \$ex->getMessage());" . PHP_EOL;
    $fileContent .= "        }" . PHP_EOL;
    $fileContent .= "    }" . PHP_EOL . PHP_EOL;

    $fileContent .= "    public function update() {" . PHP_EOL;
    $fileContent .= "        try {" . PHP_EOL;
    $fileContent .= "            parent::update();" . PHP_EOL;
    $fileContent .= "        } catch (\Exception \$ex) {" . PHP_EOL;
    $fileContent .= "            error_log('ERROR: ' . __CLASS__ . '->update: ' . \$ex->getMessage());" . PHP_EOL;
    $fileContent .= "        }" . PHP_EOL;
    $fileContent .= "    }" . PHP_EOL;

    $fileContent .= "}";
    fwrite($newModelFile, $fileContent);
    fclose($newModelFile);
}


