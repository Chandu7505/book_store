<?php
require_once 'db.php';

class BookController
{
    private $pdo;
    private $method;
    private $uri;
    private $id;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
        $this->method = $_SERVER['REQUEST_METHOD'];
        
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $parts = explode('/', trim($path, '/'));
    
        // Find where 'api' is
        $apiIndex = array_search('api', $parts);
    
        // The ID comes after 'api' + 'book.php' or whatever
        $this->id = isset($parts[$apiIndex + 1]) ? intval($parts[$apiIndex + 1]) : null;
    }
    

    public function handleRequest()
    {
        switch ($this->method) {
            case 'GET':
                $this->handleGet();
                break;
            case 'POST':
                $this->handlePost();
                break;
            case 'PUT':
                $this->handlePut();
                break;
            case 'DELETE':
                $this->handleDelete();
                break;
            default:
                http_response_code(405);
                echo json_encode(['error' => 'Method not allowed']);
                break;
        }
    }

    private function handleGet()
    {
        if (isset($_GET['search'])) {
            $stmt = $this->pdo->prepare("SELECT * FROM books WHERE title LIKE ?");
            $stmt->execute(['%' . $_GET['search'] . '%']);
        } elseif (!empty($_GET['author']) || !empty($_GET['year'])) {
            $query = "SELECT * FROM books WHERE 1=1";
            $params = [];
            if (!empty($_GET['author'])) {
                $query .= " AND author = ?";
                $params[] = $_GET['author'];
            }
            if (!empty($_GET['year'])) {
                $query .= " AND publication_year = ?";
                $params[] = $_GET['year'];
            }
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($params);
        } elseif ($this->id) {
            $stmt = $this->pdo->prepare("SELECT * FROM books WHERE id = ?");
            $stmt->execute([$this->id]);
        } else {
            $limit = $_GET['limit'] ?? 10;
            $offset = $_GET['offset'] ?? 0;
            $stmt = $this->pdo->prepare("SELECT * FROM books LIMIT ? OFFSET ?");
            $stmt->bindValue(1, (int)$limit, PDO::PARAM_INT);
            $stmt->bindValue(2, (int)$offset, PDO::PARAM_INT);
            $stmt->execute();
        }

        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    private function handlePost()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        if (!$data) {
            http_response_code(400);
            echo json_encode(["error" => "Invalid input"]);
            return;
        }

        $stmt = $this->pdo->prepare("INSERT INTO books (title, author, isbn, publication_year) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$data['title'], $data['author'], $data['isbn'], $data['publication_year']])) {
            http_response_code(201);
            echo json_encode(['message' => 'Book created']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to create book']);
        }
    }

    private function handlePut()
    {
        if (!$this->id) {
            http_response_code(400);
            echo json_encode(['error' => 'Book ID required']);
            return;
        }

        $data = json_decode(file_get_contents("php://input"), true);
        $stmt = $this->pdo->prepare("UPDATE books SET title=?, author=?, isbn=?, publication_year=? WHERE id=?");
        if ($stmt->execute([$data['title'], $data['author'], $data['isbn'], $data['publication_year'], $this->id])) {
            echo json_encode(['message' => 'Book updated']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to update book']);
        }
    }

    private function handleDelete()
    {
        error_log("Attempting to delete book with ID: " . $this->id); 
        if (!$this->id) {
            http_response_code(400);
            echo json_encode(['error' => 'Book ID required']);
            return;
        }

        $stmt = $this->pdo->prepare("DELETE FROM books WHERE id=?");
        if ($stmt->execute([$this->id])) {
            echo json_encode(['message' => 'Book deleted']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to delete book']);
        }
    }
}
?>
