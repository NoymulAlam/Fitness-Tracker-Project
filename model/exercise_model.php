<?php
function connectDB() {
    $conn = new mysqli("localhost", "root", "", "fitness_tracker");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

function createExerciseTable($conn) {
    $conn->query("CREATE TABLE IF NOT EXISTS exercises (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100),
        body_part VARCHAR(50),
        equipment VARCHAR(100)
    )");
}

function preloadDummyExercises($conn) {
    $res = $conn->query("SELECT COUNT(*) as total FROM exercises");
    $count = $res->fetch_assoc()['total'];
    if ($count == 0) {
        $dummy = [
            ["Bench Press", "Chest", "Barbell"],
            ["Push Up", "Chest", "Bodyweight"],
            ["Pull Up", "Back", "Bodyweight"],
            ["Deadlift", "Back", "Barbell"],
            ["Squat", "Legs", "Barbell"],
            ["Lunge", "Legs", "Dumbbell"],
            ["Shoulder Press", "Shoulders", "Dumbbell"],
            ["Lateral Raise", "Shoulders", "Dumbbell"]
        ];
        foreach ($dummy as $d) {
            $stmt = $conn->prepare("INSERT INTO exercises (name, body_part, equipment) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $d[0], $d[1], $d[2]);
            $stmt->execute();
            $stmt->close();
        }
    }
}

function getExercises($conn, $filter = '') {
    if ($filter) {
        $stmt = $conn->prepare("SELECT * FROM exercises WHERE body_part = ?");
        $stmt->bind_param("s", $filter);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result;
    } else {
        return $conn->query("SELECT * FROM exercises");
    }
}
?>
