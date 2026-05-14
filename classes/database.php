<?php
class database
{
    function opencon(): PDO
    {
        return new PDO(

            dsn: 'mysql:host=localhost;
            dbname=librarymanagement2',
            username: 'root',
            password: ''
        );
    }

    function insertUser($email, $password_hash, $is_active)
    {
        $con = $this->opencon();

        try {
            $con->beginTransaction();
            $stmt = $con->prepare('INSERT INTO Users (username, user_password_hash, is_active) VALUES (?, ?, ?)');
            $stmt->execute([$email, $password_hash, $is_active]);
            $user_id = $con->lastInsertId();
            $con->commit();

            return $user_id;
        } catch (PDOException $e) {
            if ($con->inTransaction()) {
                $con->rollBack();
            }
        }
    }

    function insertBorrowers($firstname, $lastname, $email, $phone, $member_since, $is_active)
    {
        $con = $this->opencon();

        try {
            $con->beginTransaction();
            $stmt = $con->prepare('INSERT INTO Borrowers (borrower_firstname, borrower_lastname, borrower_email, borrower_phone_number, borrower_member_since, is_active) VALUES (?, ?, ?, ?, ?, ?)');
            $stmt->execute([$firstname, $lastname, $email, $phone, $member_since, $is_active]);
            $borrower_id = $con->lastInsertId();
            $con->commit();

            return $borrower_id;
        } catch (PDOException $e) {
            if ($con->inTransaction()) {
                $con->rollBack();
            }
        }
    }

    function insertBorrowerUser($user_id, $borrower_id)
    {
        $con = $this->opencon();

        try {
            $con->beginTransaction();
            $stmt = $con->prepare('INSERT INTO Borrower_User (user_id, borrower_id) VALUES (?, ?)');
            $stmt->execute([$user_id, $borrower_id]);
            $bu_id = $con->lastInsertId();
            $con->commit();

            return true;
        } catch (PDOException $e) {
            if ($con->inTransaction()) {
                $con->rollBack();
            }
        }
    }

    function viewBorrowerUser()
    {
        $con = $this->opencon();
        return $con->query("SELECT * from Borrowers")->fetchAll();
    }

    // dividah
    function insertBorrowerAddress($borrower_id, $ba_house_number, $ba_street, $ba_barangay, $ba_city, $ba_province, $ba_postal_code, $is_primary)
    {
        $con = $this->opencon();

        try {
            $con->beginTransaction();
            $stmt = $con->prepare('INSERT INTO borrower_address (borrower_id, ba_house_number, ba_street, ba_barangay, ba_city, ba_province, ba_postal_code, is_primary) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
            $stmt->execute([$borrower_id, $ba_house_number, $ba_street, $ba_barangay, $ba_city, $ba_province, $ba_postal_code, $is_primary]);
            $ba_id = $con->lastInsertId();
            $con->commit();

            return true;
        } catch (PDOException $e) {
            if ($con->inTransaction()) {
                $con->rollBack();
            }
        }
    }


    function insertBooks($book_title, $book_isbn, $book_publication_year, $book_edition, $book_publisher)
    {
        $con = $this->opencon();

        try {
            $con->beginTransaction();
            $stmt = $con->prepare('INSERT INTO books (book_title, book_isbn, book_publication_year, book_edition, book_publisher) VALUES (?, ?, ?, ?, ?)');
            $stmt->execute([$book_title, $book_isbn, $book_publication_year, $book_edition, $book_publisher]);
            $book_id = $con->lastInsertId();
            $con->commit();

            return true;
        } catch (PDOException $e) {
            if ($con->inTransaction()) {
                $con->rollBack();
            }
        }
    }

    function insertBookCopy($book_id, $bc_status)
    {
        $con = $this->opencon();

        try {
            $con->beginTransaction();
            $stmt = $con->prepare('INSERT INTO book_copy (book_id, bc_status) VALUES (?, ?)');
            $stmt->execute([$book_id, $bc_status]);
            $copy_id = $con->lastInsertId();
            $con->commit();

            return true;
        } catch (PDOException $e) {
            if ($con->inTransaction()) {
                $con->rollBack();
            }
        }
    }

    function viewBook()
    {
        $con = $this->opencon();
        return $con->query("SELECT * from books")->fetchAll();
    }

    function viewBooks()
    {
        $con = $this->opencon();
        return $con->query("SELECT
            books.book_id,
            books.book_title,
            books.book_isbn,
            books.book_publication_year,
            books.book_publisher,
            COUNT(book_copy.copy_id) AS Copies,
            COALESCE(SUM(book_copy.bc_status = 'AVAILABLE'), 0) AS Available_Copies
        FROM
            books
        LEFT JOIN book_copy ON books.book_id = book_copy.book_id
        GROUP BY books.book_id")->fetchAll();
    }

    function insertBookAuthor($book_id, $author_id)
    {
        $con = $this->opencon();

        try {
            $con->beginTransaction();
            $stmt = $con->prepare('INSERT INTO book_authors (book_id, author_id) VALUES (?, ?)');
            $stmt->execute([$book_id, $author_id]);
            $ba_id = $con->lastInsertId();
            $con->commit();

            return true;
        } catch (PDOException $e) {
            if ($con->inTransaction()) {
                $con->rollBack();
            }
        }
    }

    function viewAuthors()
    {
        $con = $this->opencon();
        return $con->query("SELECT * from author")->fetchAll();
    }

    function insertBookGenre($book_id, $genre_id)
    {
        $con = $this->opencon();

        try {
            $con->beginTransaction();
            $stmt = $con->prepare('INSERT INTO book_genre (book_id, genre_id) VALUES (?, ?)');
            $stmt->execute([$book_id, $genre_id]);
            $gb_id = $con->lastInsertId();
            $con->commit();

            return true;
        } catch (PDOException $e) {
            if ($con->inTransaction()) {
                $con->rollBack();
            }
        }
    }

    function viewGenres()
    {
        $con = $this->opencon();
        return $con->query("SELECT * from genre")->fetchAll();
    }

    function updateBook($book_id, $book_title, $book_isbn, $book_publication_year, $book_publisher)
    {
        $con = $this->opencon();

        try {
            $con->beginTransaction();

            $stmt = $con->prepare("
            UPDATE books
            SET book_title = ?,
                book_isbn = ?,
                book_publication_year = ?,
                book_publisher = ?
            WHERE book_id = ?
        ");

            $stmt->execute([$book_title, $book_isbn, $book_publication_year, $book_publisher, $book_id]);

            $con->commit();
            return true;
        } catch (PDOException $e) {
            if ($con->inTransaction()) {
                $con->rollBack();
            }
            throw $e;
        }
    }

    function countBooks()
    {
        $con = $this->opencon();
        return $con->query("SELECT COUNT(*) AS total_books FROM books")->fetch(PDO::FETCH_ASSOC)['total_books'];
    }

    function countCopies()
    {
        $con = $this->opencon();
        return $con->query("SELECT COUNT(*) AS total_copies FROM book_copy")->fetch(PDO::FETCH_ASSOC)['total_copies'];
    }

    function countLoans()
    {
        $con = $this->opencon();
        return $con->query("SELECT COUNT(*) AS total_loans FROM loan")->fetch(PDO::FETCH_ASSOC)['total_loans'];
    }

    function countOverdueItems()
    {
        $con = $this->opencon();
        return $con->query("SELECT COUNT(*) AS total_overdue FROM loan_item WHERE li_duedate < CURDATE() AND li_returned_at IS NULL")->fetch(PDO::FETCH_ASSOC)['total_overdue'];
    }

    function viewLoans()
    {
        $con = $this->opencon();
        return $con->query("SELECT
            loan.loan_id,
            CONCAT(borrowers.borrower_firstname, ' ', borrowers.borrower_lastname) AS borrower_name,
            loan.loan_date,
            loan.loan_status,
            users.username
        FROM
            loan
        JOIN borrowers ON loan.borrower_id = borrowers.borrower_id
        JOIN users ON loan.user_id = users.user_id
        GROUP BY loan.loan_id")->fetchAll();
    }

    function insertAuthor($author_firstname, $author_lastname, $author_birth_year, $author_nationality)
    {
        $con = $this->opencon();

        try {
            $con->beginTransaction();
            $stmt = $con->prepare('INSERT INTO author (author_firstname, author_lastname, author_birth_year, author_nationality ) VALUES (?, ?, ?, ?)');
            $stmt->execute([$author_firstname, $author_lastname, $author_birth_year, $author_nationality]);
            $author_id = $con->lastInsertId();
            $con->commit();

            return $author_id;
        } catch (PDOException $e) {
            if ($con->inTransaction()) {
                $con->rollBack();
            }
        }
    }

    function insertGenre($genre_name)
    {
        $con = $this->opencon();

        try {
            $con->beginTransaction();
            $stmt = $con->prepare('INSERT INTO genre (genre_name) VALUES (?)');
            $stmt->execute([$genre_name]);
            $genre_id = $con->lastInsertId();
            $con->commit();

            return $genre_id;
        } catch (PDOException $e) {
            if ($con->inTransaction()) {
                $con->rollBack();
            }
        }
    }

    public function genreExists($genre)
    {
        $con = $this->opencon();

        try {

            $query = $con->prepare("SELECT COUNT(*) FROM genre WHERE genre_name = ?");
            $query->execute([$genre]);
            return $query->fetchColumn() > 0;
        } catch (PDOException $e) {
            return false;
        }
    }

    function deleteBook($book_id)
    {
        $con = $this->opencon();

        try {
            $con->beginTransaction();

            $stmtCopies = $con->prepare("DELETE FROM book_copy WHERE book_id = ?");
            $stmtCopies->execute([$book_id]);

            $stmtGenre = $con->prepare("DELETE FROM book_genre WHERE book_id = ?");
            $stmtGenre->execute([$book_id]);

            $stmtBA = $con->prepare("DELETE FROM book_authors WHERE book_id = ?");
            $stmtBA->execute([$book_id]);

            $stmtBooks = $con->prepare("DELETE FROM books WHERE book_id = ?");
            $stmtBooks->execute([$book_id]);

            $con->commit();
            return true;
        } catch (PDOException $e) {
            if ($con->inTransaction()) {
                $con->rollBack();
            }
            throw $e;
        }
    }

    function deleteAuthor($author_id)
    {
        $con = $this->opencon();

        try {
            $con->beginTransaction();

            $stmtBA = $con->prepare("DELETE FROM book_authors WHERE author_id = ?");
            $stmtBA->execute([$author_id]);

            $stmtAuthors = $con->prepare("DELETE FROM author WHERE author_id = ?");
            $stmtAuthors->execute([$author_id]);

            $con->commit();
            return true;
        } catch (PDOException $e) {
            if ($con->inTransaction()) {
                $con->rollBack();
            }
            throw $e;
        }
    }

    function deleteGenre($genre_id)
    {
        $con = $this->opencon();

        try {
            $con->beginTransaction();

            $stmtBG = $con->prepare("DELETE FROM book_genre WHERE genre_id = ?");
            $stmtBG->execute([$genre_id]);

            $stmtGenre = $con->prepare("DELETE FROM genre WHERE genre_id = ?");
            $stmtGenre->execute([$genre_id]);

            $con->commit();
            return true;
        } catch (PDOException $e) {
            if ($con->inTransaction()) {
                $con->rollBack();
            }
            throw $e;
        }
    }

    function getActiveBorrowers()
    {
        $con = $this->opencon();
        return $con->query("SELECT borrower_id, CONCAT(borrower_firstname, ' ', borrower_lastname) AS borrower_name FROM borrowers WHERE is_active = 1;")->fetchAll();
    }

    function getAvailableCopies()
    {
        $con = $this->opencon();
        return $con->query("SELECT
    book_copy.copy_id,
    books.book_id,
    books.book_title
FROM books
JOIN book_copy ON books.book_id = book_copy.book_id
WHERE bc_status = 'AVAILABLE'
ORDER BY books.book_title")->fetchAll();
    }

    function createLoanWithItems($borrower_id, $user_id, $copy_ids, $li_duedate, $condition_out)
    {
        $con = $this->opencon();

        try {
            $con->beginTransaction();

            $insertLoanStmt = $con->prepare("INSERT INTO loan (borrower_id, user_id, loan_status, loan_date) VALUES (?, ?, 'OPEN', NOW())");

            $insertLoanStmt->execute([$borrower_id, $user_id]);
            $loan_id = $con->lastInsertId();

            $checkCopyStmt = $con->prepare("SELECT bc_status FROM book_copy WHERE copy_id = ?");


            $activeLoanStmt = $con->prepare("SELECT 
                    COUNT(*) as active_count FROM loan_item
                    JOIN loan ON loan_item.loan_id = loan.loan_id
                    WHERE loan_item.copy_id = ?
                    AND loan_item.li_returned_at IS NULL
                    AND loan.loan_status = 'OPEN' 
                    ");

            foreach ($copy_ids as $copy_id) {

                $checkCopyStmt->execute([$copy_id]);
                $bc_status = $checkCopyStmt->fetch();

                if (!$bc_status) {
                    throw new Exception("Copy ID $copy_id does not exist.");
                }

                if ($bc_status['bc_status'] !== 'AVAILABLE') {
                    throw new Exception("Copy ID $copy_id is not available.");
                }

                $activeLoanStmt->execute([$copy_id]);
                $activeLoan = $activeLoanStmt->fetch();

                if ($activeLoan['active_count'] > 0) {
                    throw new Exception("Copy already on active loan.");
                }

                $insertLoanItemStmt = $con->prepare("INSERT INTO loan_item (loan_id, copy_id, li_duedate, condition_out) VALUES (?,?,?,?) ");
            $updateCopyStmt = $con->prepare("UPDATE book_copy SET bc_status = 'ON_LOAN' WHERE copy_id = ? ");
            }
            $con->commit();
            return $loan_id;
            
            } catch (Exception $e) {
                if ($con->inTransaction()) {
                    $con->rollBack();
                }
                throw $e;
            }
    }
}

