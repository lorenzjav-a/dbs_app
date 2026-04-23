<?php
    class database{
        function opencon(): PDO{
            return new PDO(

        dsn: 'mysql:host=localhost;
            dbname=librarymanagement2',
            username: 'root',
            password: '');
        } 

        function insertUser($email, $password_hash, $is_active){
            $con = $this->opencon();

            try{
                $con->beginTransaction();
                $stmt = $con->prepare('INSERT INTO Users (username, user_password_hash, is_active) VALUES (?, ?, ?)');
                $stmt->execute([$email, $password_hash, $is_active]);
                $user_id = $con->lastInsertId();
                $con->commit();

                return $user_id;

            }catch(PDOException $e){
                if($con->inTransaction()){
                    $con->rollBack();
                }
            }
        }

        function insertBorrowers($firstname, $lastname, $email, $phone, $member_since, $is_active){
            $con = $this->opencon();

            try{
                $con->beginTransaction();
                $stmt = $con->prepare('INSERT INTO Borrowers (borrower_firstname, borrower_lastname, borrower_email, borrower_phone_number, borrower_member_since, is_active) VALUES (?, ?, ?, ?, ?, ?)');
                $stmt->execute([$firstname, $lastname, $email, $phone, $member_since, $is_active]);
                $borrower_id = $con->lastInsertId();
                $con->commit();

                return $borrower_id;

            }catch(PDOException $e){
                if($con->inTransaction()){
                    $con->rollBack();
                }
            }
        }

        function insertBorrowerUser($user_id, $borrower_id){
            $con = $this->opencon();

            try{
                $con->beginTransaction();
                $stmt = $con->prepare('INSERT INTO Borrower_User (user_id, borrower_id) VALUES (?, ?)');
                $stmt->execute([$user_id, $borrower_id]);
                $bu_id = $con->lastInsertId();
                $con->commit();

                return true;

            } catch(PDOException $e){
                if($con->inTransaction()){
                    $con->rollBack();
                }
            }
        }

        function viewBorrowerUser(){
            $con =$this->opencon();
            return $con->query("SELECT * from Borrowers")->fetchAll();
        }

// dividah
        function insertBorrowerAddress($borrower_id, $ba_house_number, $ba_street, $ba_barangay, $ba_city, $ba_province, $ba_postal_code, $is_primary){
            $con = $this->opencon();

            try{
                $con->beginTransaction();
                $stmt = $con->prepare('INSERT INTO borrower_address (borrower_id, ba_house_number, ba_street, ba_barangay, ba_city, ba_province, ba_postal_code, is_primary) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
                $stmt->execute([$borrower_id, $ba_house_number, $ba_street, $ba_barangay, $ba_city, $ba_province, $ba_postal_code, $is_primary]);
                $ba_id = $con->lastInsertId();
                $con->commit();

                return true;

            }catch(PDOException $e){
                if($con->inTransaction()){
                    $con->rollBack();
                }
            }
        }


        function insertBooks($book_title, $book_isbn, $book_publication_year, $book_edition, $book_publisher){
            $con = $this->opencon();

            try{
                $con->beginTransaction();
                $stmt = $con->prepare('INSERT INTO books (book_title, book_isbn, book_publication_year, book_edition, book_publisher) VALUES (?, ?, ?, ?, ?)');
                $stmt->execute([$book_title, $book_isbn, $book_publication_year, $book_edition, $book_publisher]);
                $book_id = $con->lastInsertId();
                $con->commit();

                return true;

            }catch(PDOException $e){
                if($con->inTransaction()){
                    $con->rollBack();
                }
            }
        }

        function insertBookCopy($book_id, $bc_status){
            $con = $this->opencon();

            try{
                $con->beginTransaction();
                $stmt = $con->prepare('INSERT INTO book_copy (book_id, bc_status) VALUES (?, ?)');
                $stmt->execute([$book_id, $bc_status]);
                $copy_id = $con->lastInsertId();
                $con->commit();

                return true;

            }catch(PDOException $e){
                if($con->inTransaction()){
                    $con->rollBack();
                }
            }

        }

        function viewBook(){
            $con =$this->opencon();
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
        
        function insertBookAuthor($book_id, $author_id){
            $con = $this->opencon();

            try{
                $con->beginTransaction();
                $stmt = $con->prepare('INSERT INTO book_authors (book_id, author_id) VALUES (?, ?)');
                $stmt->execute([$book_id, $author_id]);
                $copy_id = $con->lastInsertId();
                $con->commit();

                return true;

            }catch(PDOException $e){
                if($con->inTransaction()){
                    $con->rollBack();
                }
            }

        }

        function viewAuthors(){
            $con =$this->opencon();
            return $con->query("SELECT * from author")->fetchAll();
        }

        function insertBookGenre($book_id, $genre_id){
            $con = $this->opencon();

            try{
                $con->beginTransaction();
                $stmt = $con->prepare('INSERT INTO book_genre (book_id, genre_id) VALUES (?, ?)');
                $stmt->execute([$book_id, $genre_id]);
                $copy_id = $con->lastInsertId();
                $con->commit();

                return true;

            }catch(PDOException $e){
                if($con->inTransaction()){
                    $con->rollBack();
                }
            }

        }

        function viewGenres(){
            $con =$this->opencon();
            return $con->query("SELECT * from genre")->fetchAll();
        }
        
        function updateBook($book_id, $book_title, $book_isbn, $book_publication_year, $book_publisher) {
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

        function countBooks(){
            $con = $this->opencon();
            return $con->query("SELECT COUNT(*) AS total_books FROM books")->fetch(PDO::FETCH_ASSOC)['total_books'];
        }

        function countCopies(){
            $con = $this->opencon();
            return $con->query("SELECT COUNT(*) AS total_copies FROM book_copy")->fetch(PDO::FETCH_ASSOC)['total_copies'];
        }

        function countLoans(){
            $con = $this->opencon();
            return $con->query("SELECT COUNT(*) AS total_loans FROM loan")->fetch(PDO::FETCH_ASSOC)['total_loans'];
        }

        function countOverdueItems(){
            $con = $this->opencon();
            return $con->query("SELECT COUNT(*) AS total_overdue FROM loan_item WHERE li_duedate < CURDATE() AND li_returned_at IS NULL")->fetch(PDO::FETCH_ASSOC)['total_overdue'];
        }

     }

?>