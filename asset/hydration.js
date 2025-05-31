function hydrationReminder() {
  if (typeof totalToday !== 'undefined' && typeof dailyGoal !== 'undefined') {
    if (totalToday < dailyGoal) {
      document.getElementById("reminder").style.display = "block";
    }
  }
}
setInterval(hydrationReminder, 60000); // Check every minute
window.onload = hydrationReminder;
