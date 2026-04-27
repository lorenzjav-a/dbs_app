<?php
require_once('../classes/database.php');
$con = new database();

$allauthors = $con->viewAuthors();
$allgenres = $con->viewGenres(); 


$authorCreateStatus = null;
$authorCreateMessage = '';


  if(isset($_POST['add_author'])){
    
    $firstname = $_POST['author_firstname'];
    $lastname = $_POST['author_lastname'];
    $birth_year = $_POST['author_birth_year'] ?? null;
    $nationality = $_POST['author_nationality'] ?? null;


    try {

    
    $author_id = $con->insertAuthor($firstname, $lastname, $birth_year, $nationality);

    $authorCreateStatus = 'success';
    $authorCreateMessage = 'Author created successfully.';


    } catch (Exception $e){

      $authorCreateStatus = 'error';
      $authorCreateMessage = $e->getMessage();

    }

}



$genreCreateStatus = null;
$genreCreateMessage = '';

if(isset($_POST['add_genre'])){
    $genre = ($_POST['genre_name']); 

    // Check if it exists FIRST
    if($con->genreExists($genre)){
        $genreCreateStatus = 'error'; 
        $genreCreateMessage = 'That genre already exists in the database.';
    } else {
        try {
            $genre_id = $con->insertGenre($genre);
            $genreCreateStatus = 'success';
            $genreCreateMessage = 'Genre created successfully.';
        } catch (Exception $e){
            $genreCreateStatus = 'error';
            $genreCreateMessage = $e->getMessage();
        }
    }
}

?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Authors and Genres - Admin (Teaching Demo)</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="../assets/css/style.css" />
  <link rel="stylesheet" href="../sweetalert/dist/sweetalert2.css">
  <link rel="stylesheet" href="../bootstrap-5.3.3-dist/css/bootstrap.css">
  
</head>
<body>
<nav class="navbar navbar-expand-lg bg-white border-bottom sticky-top">
  <div class="container">
    <a class="navbar-brand fw-semibold" href="admin-dashboard.php">Library Admin</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navAdminStatic">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div id="navAdminStatic" class="collapse navbar-collapse">
      <ul class="navbar-nav me-auto gap-lg-1">
        <li class="nav-item"><a class="nav-link" href="admin-dashboard.php">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="books.php">Books</a></li>
        <li class="nav-item"><a class="nav-link" href="borrowers.php">Borrowers</a></li>
        <li class="nav-item"><a class="nav-link active" href="authors-genres.php">Authors &amp; Genres</a></li>
        <li class="nav-item"><a class="nav-link" href="checkout.php">Checkout</a></li>
        <li class="nav-item"><a class="nav-link" href="return.php">Return</a></li>
        <li class="nav-item"><a class="nav-link" href="catalog.php">Catalog</a></li>
      </ul>
      <div class="d-flex align-items-center gap-2">
        <span class="badge badge-soft">Role: ADMIN</span>
        <a class="btn btn-sm btn-outline-secondary" href="login.php">Logout</a>
      </div>
    </div>
  </div>
</nav>

<main class="container py-4">
  <div class="row g-3">

    <div class="col-12 col-lg-6">
      <div class="card p-4 h-100">
        <h5 class="mb-1">Add Author</h5>
        <p class="small-muted mb-3">Sample form for the Authors table.</p>

        <form action="#" method="POST" class="row g-2">
          <div class="col-12 col-md-6">
            <label class="form-label">First Name</label>
            <input class="form-control" name="author_firstname" placeholder="e.g., Jose" required />
          </div>
          <div class="col-12 col-md-6">
            <label class="form-label">Last Name</label>
            <input class="form-control" name="author_lastname" placeholder="e.g., Rizal" required />
          </div>
          <div class="col-12 col-md-6">
            <label class="form-label">Birth Year</label>
            <input class="form-control" name="author_birth_year" type="number" min="1" max="2100" placeholder="optional" />
          </div>
          <div class="col-12 col-md-6">
            <label class="form-label">Nationality</label>
            <input class="form-control" name="author_nationality" placeholder="optional" />
          </div>
          <div class="col-12">
            <button name="add_author" class="btn btn-primary w-100" type="submit">Save Author</button>
          </div>
        </form>
      </div>
    </div>

    <div class="col-12 col-lg-6">
      <div class="card p-4 h-100">
        <h5 class="mb-1">Add Genre</h5>
        <p class="small-muted mb-3">Sample form for the Genres table.</p>

        <form action="#" method="POST" class="row g-2">
          <div class="col-12">
            <label class="form-label">Genre Name</label>
            <input class="form-control" name="genre_name" placeholder="e.g., Classic" required />
          </div>
          <div class="col-12">
            <button name="add_genre" class="btn btn-outline-primary w-100" type="submit">Save Genre</button>
          </div>
        </form>
      </div>
    </div>

    <div class="col-12 col-lg-8">
      <div class="card p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h5 class="mb-0">Authors List</h5>
          <span class="small-muted">Static sample data</span>
        </div>
        <div class="table-responsive">
          <table class="table table-sm align-middle">
            <thead class="table-light">
              <tr>
                <th>Author ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Birth Year</th>
                <th>Nationality</th>
              </tr>
            </thead>
            <tbody>
              
                <?php
                foreach ($allauthors as $author) {
                    echo "<tr>";
                    echo "<td>{$author['author_id']}</td>";
                    echo "<td>{$author['author_firstname']}</td>";
                    echo "<td>{$author['author_lastname']}</td>";
                    echo "<td>{$author['author_birth_year']}</td>";
                    echo "<td>{$author['author_nationality']}</td>";
                    echo "</tr>";
                }
                ?>
              
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div class="col-12 col-lg-4">
      <div class="card p-4 h-100">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h5 class="mb-0">Genres List</h5>
          <span class="small-muted">Static sample data</span>
        </div>
        <div class="table-responsive">
          <table class="table table-sm align-middle">
            <thead class="table-light">
              <tr>
                <th>Genre ID</th>
                <th>Genre Name</th>
              </tr>
            </thead>
            <tbody>
              <?php
              foreach ($allgenres as $genre) {
                  echo "<tr>";
                  echo "<td>{$genre['genre_id']}</td>";
                  echo "<td>{$genre['genre_name']}</td>";
                  echo "</tr>";
              }
              ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</main>

<script src="../bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
<script src="../sweetalert/dist/sweetalert2.js"></script>

<script>

  const authorCreateStatus = <?php echo json_encode($authorCreateStatus)?>;
  const authorCreateMessage = <?php echo json_encode($authorCreateMessage)?>;

  if(authorCreateStatus == 'success'){
  Swal.fire({
  title: "Success",
  icon: "success",
  text: authorCreateMessage,
  confirmButtonText: "OK",
  	});

   } else if(authorCreateStatus == 'success'){
  Swal.fire({
  title: "Success",
  icon: "success",
  text: authorCreateMessage,
  confirmButtonText: "OK",
  	});
  
  }





</script>

<script>

  const genreCreateStatus = <?php echo json_encode($genreCreateStatus)?>;
  const genreCreateMessage = <?php echo json_encode($genreCreateMessage)?>;

  if(genreCreateStatus == 'success'){
  Swal.fire({
  title: "Success",
  icon: "success",
  text: genreCreateMessage,
  confirmButtonText: "OK",
  	});

   } else if(genreCreateStatus == 'success'){
  Swal.fire({
  title: "Success",
  icon: "success",
  text: genreCreateMessage,
  confirmButtonText: "OK",
  	});
  
  } else if(genreCreateStatus == 'error'){
    Swal.fire({
      title: "Error",
      icon: "error",
      text: genreCreateMessage,
      confirmButtonText: "OK",
    });
  }





</script>





</body>
</html>
