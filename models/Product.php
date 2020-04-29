<?php
class Product {
    // DB info
    private $conn;
    private $table = "products";

    // Post properties
    public $id;
    public $name;
    public $description;
    public $price;
    public $category_id;
    public $category_name;
    public $created;
    public $modified;

    // Constructor (pass DB in as argument)
    public function __construct($db) {
        $this->conn = $db;
    }

    // Get all products
    public function read() {
        // Create query
        $query = "SELECT
                c.name as category_name,
                p.id,
                p.name,
                p.description,
                p.price,
                p.category_id,
                p.created,
                p.modified
            FROM ". $this->table ." p
            LEFT JOIN
                categories c ON p.category_id = c.id
            ORDER BY
                p.created DESC
        ";

        // Prepared statement
        $stmt = $this->conn->prepare($query);

        // Execute query
        $stmt->execute();

        return $stmt;
    }

    // Get single product
    public function read_single() {
        // Create query
        $query = "SELECT
                c.name as category_name,
                p.id,
                p.name,
                p.description,
                p.price,
                p.category_id,
                p.created,
                p.modified
            FROM ". $this->table ." p
            LEFT JOIN
                categories c ON p.category_id = c.id
            WHERE
                p.id = ?
            LIMIT 0,1
        ";

        // Prepared statement
        $stmt = $this->conn->prepare($query);

        // Bind ID
        $stmt->bindParam(1, $this->id);

        // Execute query
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // Set properties
        $this->name = $row["name"];
        $this->description = $row["description"];
        $this->price = $row["price"];
        $this->category_id = $row["category_id"];
        $this->category_name = $row["category_name"];
        $this->created = $row["created"];
        $this->modified = $row["modified"];
    }

    // Create product
    public function create() {
        // Create query
        $query = "INSERT INTO ". $this->table ."
            SET
                name = :name,
                description = :description,
                price = :price,
                category_id = :category_id,
                created = :created,
                modified = :modified
        ";

        // Prepared statement
        $stmt = $this->conn->prepare($query);

        // Clean data
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->price = htmlspecialchars(strip_tags($this->price));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));
        $this->created = htmlspecialchars(strip_tags($this->created));
        $this->modified = htmlspecialchars(strip_tags($this->modified));

        // Bind data
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":price", $this->price);
        $stmt->bindParam(":category_id", $this->category_id);
        $stmt->bindParam(":created", $this->created);
        $stmt->bindParam(":modified", $this->modified);

        // Execure query
        if($stmt->execute()) {
            return true;
        }

        // Print error if something goes wrong
        printf("Error: %s.\n", $stmt->error);

        return false;
    }
}
