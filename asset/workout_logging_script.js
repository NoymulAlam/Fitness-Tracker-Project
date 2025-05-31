let timerInterval;
let seconds = 0;
let startTimestamp, endTimestamp;

function formatTime(sec) {
  const h = String(Math.floor(sec / 3600)).padStart(2, '0');
  const m = String(Math.floor((sec % 3600) / 60)).padStart(2, '0');
  const s = String(sec % 60).padStart(2, '0');
  return `${h}:${m}:${s}`;
}

function startTimer() {
  startTimestamp = new Date();
  document.getElementById('start_time').value = startTimestamp.toISOString().slice(0, 19).replace('T', ' ');
  timerInterval = setInterval(() => {
    seconds++;
    document.getElementById("timer").textContent = formatTime(seconds);
  }, 1000);
}

function stopTimer() {
  clearInterval(timerInterval);
  endTimestamp = new Date();
  document.getElementById('end_time').value = endTimestamp.toISOString().slice(0, 19).replace('T', ' ');
  document.getElementById('duration').value = formatTime(seconds);
}

let exercises = [];

function addExercise() {
  const name = document.getElementById("exercise").value;
  const sets = document.getElementById("sets").value;
  const reps = document.getElementById("reps").value;
  const weight = document.getElementById("weight").value;

  if (!name || !sets || !reps || !weight) {
    alert("Please fill all exercise fields.");
    return;
  }

  const exercise = { name, sets, reps, weight };
  exercises.push(exercise);
  renderExercises();

  // Clear input fields after adding
  document.getElementById("exercise").value = "";
  document.getElementById("sets").value = "";
  document.getElementById("reps").value = "";
  document.getElementById("weight").value = "";
}

function renderExercises() {
  const tbody = document.getElementById("exerciseTable").querySelector("tbody");
  tbody.innerHTML = "";
  exercises.forEach((ex, i) => {
    const row = `<tr>
      <td>${ex.name}</td>
      <td>${ex.sets}</td>
      <td>${ex.reps}</td>
      <td>${ex.weight}</td>
      <td><button type="button" onclick="removeExercise(${i})">Remove</button></td>
    </tr>`;
    tbody.innerHTML += row;
  });
}

function removeExercise(index) {
  exercises.splice(index, 1);
  renderExercises();
}

function prepareForm() {
  if (!document.getElementById('start_time').value || !document.getElementById('end_time').value) {
    alert("Please start and stop the timer before submitting.");
    return false;
  }
  document.getElementById("exercise_data").value = JSON.stringify(exercises);
  return true;
}
