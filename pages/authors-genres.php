<?php
require_once('../classes/database.php');
session_start();
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

if(isset($_POST['delete_author'])){
    $author_id = $_POST['author_id'];
    $author_name = $_POST['author_name'];
    $_SESSION['author_name'] = $author_name;

    try {

    $con->deleteAuthor($author_id);
    $_SESSION['success_messager'] = $author_name . ' has been deleted from the database. ';
    header('Location: authors-genres.php');
    exit();
    
    } catch (Exception $e){

    $_SESSION['error_messager'] = "Cannot delete this author. It may have active books associated with it.";
      
    }

}

if(isset($_POST['delete_genre'])){
    $genre_id = $_POST['genre_id'];
    $genre_name = $_POST['genre_name'];
    $_SESSION['genre_name'] = $genre_name;

    try {

    $con->deleteGenre($genre_id);
    $_SESSION['success_message'] = $genre_name . ' has been deleted from the database. ';
    header('Location: authors-genres.php');
    exit();
    
    } catch (Exception $e){

    $_SESSION['error_message'] = "Cannot delete this genre. It may have active books associated with it.";
      
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

<?php if(isset($_SESSION['success_messager'])){ ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
  <strong>Success!</strong> <?php echo $_SESSION['success_messager']; ?>
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
   
  </button>
</div>
<?php 
 unset($_SESSION['success_messager']);
} ?>

<?php if(isset($error_messager)){ ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
  <strong>Error</strong> <?php echo $error_messager; ?>
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
   
  </button>
</div>
<?php } ?>



  
<?php if(isset($_SESSION['success_message'])){ ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
  <strong>Success!</strong> <?php echo $_SESSION['success_message']; ?>
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
   
  </button>
</div>
<?php 
 unset($_SESSION['success_message']);
} ?>

<?php if(isset($error_message)){ ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
  <strong>Error</strong> <?php echo $error_message; ?>
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
   
  </button>
</div>
<?php } ?>


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
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              
                <?php
                foreach ($allauthors as $author) {
                    echo '<tr>';
                    echo "<td>{$author['author_id']}</td>";
                    echo "<td>{$author['author_firstname']}</td>";
                    echo "<td>{$author['author_lastname']}</td>";
                    echo "<td>{$author['author_birth_year']}</td>";
                    echo "<td>{$author['author_nationality']}</td>";
                    
                    echo'<td>';
                    echo'<button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAuthorModal"

            data-author-id="'.$author['author_id'].'"
            data-author-name="'.$author['author_firstname'] . ' ' . $author['author_lastname'].'"

            >Delete</button>';

            echo'</td>';
            echo'</tr>';
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
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php
              foreach ($allgenres as $genre) {
                  echo "<tr>";
                  echo "<td>{$genre['genre_id']}</td>";
                  echo "<td>{$genre['genre_name']}</td>";

                  echo'<td>';
                  echo'<button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteGenreModal"

            data-genre-id="'.$genre['genre_id'].'"
            data-genre-name="'.$genre['genre_name'].'"

            >Delete</button>';

                  echo '</td>'; 
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

<div class="modal fade" id="deleteAuthorModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog"> 
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Delete Author</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">


        <p> Are you sure you want to delete <strong id="delete_author_name"></strong>?</p>
        <p class="text-danger small"> this action cannot be undone.</p>

        <form action="#" method="POST">
          <input type="hidden" name="author_id" id="delete_author_id">
          <input type="hidden" name="author_name" id="delete_authors_name">

        <div class="d-flex gap-2 justify-content-end">
          <button type="button" class ="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>

          <button type="submit" class ="btn btn-danger" name="delete_author">Delete</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="deleteGenreModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog"> 
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Delete Genre</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">


        <p> Are you sure you want to delete <strong id="delete_genre_name"></strong>?</p>
        <p class="text-danger small"> this action cannot be undone.</p>

        <form action="#" method="POST">
          <input type="hidden" name="genre_id" id="delete_genre_id">
          <input type="hidden" name="genre_name" id="delete_genres_name">

        <div class="d-flex gap-2 justify-content-end">
          <button type="button" class ="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>

          <button type="submit" class ="btn btn-danger" name="delete_genre">Delete</button>
    </div>
  </div>
</div>

<script src="../bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
<script src="../sweetalert/dist/sweetalert2.js"></script>

<script>
  const deleteAuthorModal =  document.getElementById('deleteAuthorModal')
  deleteAuthorModal.addEventListener('show.bs.modal', function(event) {

  const btn = event.relatedTarget;
  if (!btn) return;

  document.getElementById('delete_author_id').value = btn.getAttribute('data-author-id') || '';
  
  document.getElementById('delete_authors_name').value = btn.getAttribute('data-author-name') || '';

  document.getElementById("delete_author_name").textContent = ' ' + (btn.getAttribute
  ('data-author-name') || '');

 });
</script>

<script>
  const deleteGenreModal =  document.getElementById('deleteGenreModal')
  deleteGenreModal.addEventListener('show.bs.modal', function(event) {

  const button = event.relatedTarget;
  if (!button) return;

  document.getElementById('delete_genre_id').value = button.getAttribute('data-genre-id') || '';
  
  document.getElementById('delete_genres_name').value = button.getAttribute('data-genre-name') || '';

  document.getElementById("delete_genre_name").textContent = ' ' + (button.getAttribute
  ('data-genre-name') || '');

 });
</script>

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
