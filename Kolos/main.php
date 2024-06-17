<?php
class Movie {
    public $id;
    public $title;
    public $director;
    public $releaseYear;
    public $genre;
    public $rating;

    public function __construct($id, $title, $director, $releaseYear, $genre, $rating) {
        $this->id = $id;
        $this->title = $title;
        $this->director = $director;
        $this->releaseYear = $releaseYear;
        $this->genre = $genre;
        $this->rating = $rating;
    }
}

function readMoviesFromCsv($filePath) {
    $movies = array();

    if (!file_exists($filePath)) {
        throw new Exception("Nie odnaleziono pliku!");
    }

    if (($handle = fopen($filePath, "r")) !== FALSE) {
        $header = fgetcsv($handle, 1000, ",");
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            if (count($data) != 6) {
                throw new Exception("Niepoprawny format danych!");
            }
            $movies[] = new Movie($data[0], $data[1], $data[2], $data[3], $data[4], $data[5]);
        }
        fclose($handle);
    } else {
        throw new Exception("Nie można otworzyć pliku!");
    }

    return $movies;
}

function sortMovies(&$movies, $column, $direction) {
    usort($movies, function ($a, $b) use ($column, $direction) {
        if ($a->$column == $b->$column) {
            return 0;
        }
        if ($direction == 'asc') {
            return ($a->$column < $b->$column) ? -1 : 1;
        } else {
            return ($a->$column > $b->$column) ? -1 : 1;
        }
    });
}

function generateReport($filePath) {
    $categoryCounts = array();
    $categoryRatings = array();

    if (!file_exists($filePath)) {
        throw new Exception("Nie odnalezono pliku!");
    }

    if (($handle = fopen($filePath, "r")) !== FALSE) {
        $header = fgetcsv($handle, 1000, ",");
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            if (count($data) != 6) {
                throw new Exception("Niepoprawny format danych!");
            }
            $genre = $data[4];
            $rating = (float) $data[5];

            if (!isset($categoryCounts[$genre])) {
                $categoryCounts[$genre] = 0;
                $categoryRatings[$genre] = 0;
            }

            $categoryCounts[$genre]++;
            $categoryRatings[$genre] += $rating;
        }
        fclose($handle);
    } else {
        throw new Exception("Nie można otworzyć pliku");
    }

    $report = array();
    foreach ($categoryCounts as $genre => $count) {
        $averageRating = $categoryRatings[$genre] / $count;
        $report[$genre] = array(
            'count' => $count,
            'average_rating' => $averageRating
        );
    }

    return $report;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $director = $_POST['director'];
    $releaseYear = $_POST['releaseYear'];
    $genre = $_POST['genre'];
    $rating = $_POST['rating'];

    $error = '';

    if (empty($id) || empty($title) || empty($director) || empty($releaseYear) || empty($genre) || empty($rating)) {
        $error = "Uzupełnij wszytkie pola.";
    } elseif (!is_numeric($releaseYear) || !is_numeric($rating) || $rating < 0 || $rating > 10) {
        $error = "Niepoprawny format danych.";
    } else {
        $newMovie = array($id, $title, $director, $releaseYear, $genre, $rating);
        $file = fopen('movies.csv', 'a');
        fputcsv($file, $newMovie);
        fclose($file);

        header('Location: '.$_SERVER['PHP_SELF']);
        exit();
    }
}

try {
    $movies = readMoviesFromCsv('movies.csv');
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
    die();
}

$sortColumn = isset($_GET['sort']) ? $_GET['sort'] : 'id';
$sortDirection = isset($_GET['dir']) ? $_GET['dir'] : 'asc';

sortMovies($movies, $sortColumn, $sortDirection);

try {
    $report = generateReport('movies.csv');
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
    die();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Movies Database</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            background: floralwhite;
        }
        .container {
            width: 80%;
            margin: auto;
        }
        table {
            background: lightgray;
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th a {
            text-decoration: none;
            color: black;
            display: block;
        }
        th a:hover {
            text-decoration: underline;
        }
        .error {
            color: red;
        }
        .report {
            margin-top: 20px;
        }
        input
        {
            margin-top: 0.5em;
            background: ghostwhite;
        }
        .tlo{
            background: darkgray;
        }
    </style>
</head>
<body>

<div class="container">

    <h2>Baza z filmami</h2>

    <h3>Dodaj nowy film</h3>
    <?php if (!empty($error)): ?>
        <p class="error"><?php echo $error; ?></p>
    <?php endif; ?>
    <form method="POST">
        <label>ID:</label> <input placeholder="ID" name="id"><br>
        <label>Tytuł:</label> <input placeholder="Kubuś i Hefalumpy" name="title"><br>
        <label>Reżyser:</label> <input placeholder="Frank Nissen" name="director"><br>
        <label>Rok Wydania:</label> <input placeholder="2005" name="releaseYear"><br>
        <label>Gatunek:</label> <input placeholder="Animacja" name="genre"><br>
        <label>Ocena:</label> <input placeholder="10" name="rating"><br>
        <input type="submit" value="Dodaj film">
    </form>

    <h3>Lista filmów</h3>
    <table>
        <thead>
        <tr>
            <th class="tlo" ><a href="?sort=id&dir=<?php echo $sortDirection == 'asc' ? 'desc' : 'asc'; ?>">ID</a></th>
            <th class="tlo"><a href="?sort=title&dir=<?php echo $sortDirection == 'asc' ? 'desc' : 'asc'; ?>">Tytuł</a></th>
            <th class="tlo"><a href="?sort=director&dir=<?php echo $sortDirection == 'asc' ? 'desc' : 'asc'; ?>">Reżyser</a></th>
            <th class="tlo"><a href="?sort=releaseYear&dir=<?php echo $sortDirection == 'asc' ? 'desc' : 'asc'; ?>">Rok Wydania</a></th>
            <th class="tlo"><a href="?sort=genre&dir=<?php echo $sortDirection == 'asc' ? 'desc' : 'asc'; ?>">Gatunek</a></th>
            <th class="tlo"><a href="?sort=rating&dir=<?php echo $sortDirection == 'asc' ? 'desc' : 'asc'; ?>">Ocena</a></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($movies as $movie): ?>
        <tr>
            <td><?php echo htmlspecialchars($movie->id); ?></td>
            <td><?php echo htmlspecialchars($movie->title); ?></td>
            <td><?php echo htmlspecialchars($movie->director); ?></td>
            <td><?php echo htmlspecialchars($movie->releaseYear); ?></td>
            <td><?php echo htmlspecialchars($movie->genre); ?></td>
            <td><?php echo htmlspecialchars($movie->rating); ?></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <h3>Raport filmów z bazy</h3>
    <div class="report">
        <table>
            <thead>
            <tr>
                <th class="tlo">Gatunek</th>
                <th class="tlo">Liczba filmów</th>
                <th class="tlo">Średnia ocena</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($report as $genre => $data): ?>
                <tr>
                    <td><?php echo htmlspecialchars($genre); ?></td>
                    <td><?php echo htmlspecialchars($data['count']); ?></td>
                    <td><?php echo number_format($data['average_rating'], 1); ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div>

</body>
</html>

