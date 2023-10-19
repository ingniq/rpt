<?php

declare(strict_types=1);

/**
 * Enum representing the possible result types.
 */
enum ResultType
{
    /**
     * Represents a normal result.
     */
    case normal;
     /**
     * Represents a successful result.
     */
    case success;
    /**
     * Represents a failure result.
     */
    case failure;
}

/**
 * Class Init
 * 
 * A class that represents the test table and contains methods for creating, filling, and retrieving data from the table.
 */
final class Init
{
    /**
     * The name of the SQLite database file.
     */
    const DB_NAME = 'test.db';
    /**
     * The name of the table in the database.
     */
    const DB_TABLE_NAME = 'test';
    /**
     * The limit for the number of records to insert into the table.
     */
    const DB_INSERT_LIMIT = 10;

    /**
     * @var SQLite3 The SQLite database connection.
     */
    private $db;

    /**
     * Class constructor
     * 
     * Executes the create and fill methods when creating an object of the class.
     * 
     * @param string $dbFolder The path to the sqlite db folder
     */
    public function __construct($dbFolder)
    {
        $this->db = new SQLite3($dbFolder . '/' . self::DB_NAME);
        $this->create();
        $this->fill();

        if ($this->db->lastErrorMsg() !== 'not an error') {
            die('DB error: ' . $this->db->lastErrorMsg());
        }
    }

    /**
     * Method create
     * 
     * Creates, if it does not exist, a test table containing 5 fields.
     */
    private function create(): void
    {
        $tableName = self::DB_TABLE_NAME;

        $query = "CREATE TABLE IF NOT EXISTS {$tableName} (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name VARCHAR(255),
            created_at DATETIME,
            updated_at DATETIME,
            result TEXT
        )";
        $this->db->exec($query);
    }

    /**
     * Method fill
     * 
     * Fills the test table with random data.
     */
    private function fill(): void
    {
        $tableName = self::DB_TABLE_NAME;
        $query = "INSERT INTO {$tableName} (name, created_at, updated_at, result) VALUES ";
        $randomTimestampFormatString = "%d days +%d hours +%d minutes +%d seconds";
        $insertingValuesRows = [];

        for ($i = 0; $i < self::DB_INSERT_LIMIT; $i++) {
            $randomValues = $this->GenerateRundomValues($randomTimestampFormatString);
            $insertingValuesRow = "('" . implode("', '", $randomValues) . "')";
            $insertingValuesRows[] = $insertingValuesRow;
        }

        $query .= implode(", ", $insertingValuesRows);
        $this->db->exec($query);
    }

    /**
     * Method GenerateRundomValues
     * 
     * Generates an array of random insertingValuesRows.
     * 
     * @param string $randomTimestampFormatString The format string for generating random dates.
     * @return array An array of randomly generated insertingValuesRows
     */
    private function GenerateRundomValues(string $randomTimestampFormatString): array
    {
        $values = [];
        // random name
        $values[] = "Name " . rand(0, 1000);

        // [days, hours, minutes, seconds]
        $createdAtRandomParams = [rand(0, 3), rand(0, 23), rand(0, 59), rand(0, 59)];
        $createdAt = self::GetDateTime("-" . $randomTimestampFormatString, $createdAtRandomParams);
        // random createdAt
        $values[] = $createdAt;

        // [createdAt, days, hours, minutes, seconds]
        $updatedAtRandomParams = [$createdAt, rand(0, 3), rand(0, 23), rand(0, 59), rand(0, 59)];
        // random updatedAt
        $values[] = self::GetDateTime("%s +" . $randomTimestampFormatString, $updatedAtRandomParams);

        // random result
        $values[] = match (rand(0, 9) % 3) {
            0 => ResultType::normal->name,
            1 => ResultType::success->name,
            default => ResultType::failure->name,
        };

        return $values;
    }

    /**
     * Method GetDateTime
     * 
     * Formats a date and time string based on the given time format and parameters.
     * 
     * @param string $timeFormat The format string for the date and time
     * @param array $timeFormatParams An array of parameters to be used in the format string
     * @return string The formatted date and time string
     */
    public static function GetDateTime(string $timeFormat, array $timeFormatParams): string
    {
        return date('Y-m-d H:i:s', strtotime(vsprintf($timeFormat, $timeFormatParams)));
    }

    /**
     * Method get
     * 
     * Retrieves data from the test table based on the criteria: result among the values 'normal' and 'success'.
     * 
     * @return array An array of data that satisfies the criteria
     */
    public function get(): array
    {
        $tableName = self::DB_TABLE_NAME;
        $query = "SELECT * FROM {$tableName} WHERE result IN ('normal', 'success')";
        $result = $this->db->query($query);

        $data = [];
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $data[] = $row;
        }

        return $data;
    }
}

// Creating an object of the Init class with the folder path as a parameter
$dbFolder = isset($argv[1]) ? $argv[1] : './db';
$table = new Init($dbFolder);
$data = $table->get();
print_r($data);