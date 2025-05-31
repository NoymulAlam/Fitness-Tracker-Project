function quickAdd(meal) {
    const meals = {
        "Grilled Chicken": {carbs: 0, protein: 30, fat: 5, calories: 180},
        "Oatmeal with Banana": {carbs: 45, protein: 6, fat: 4, calories: 230},
        "Salmon with Rice": {carbs: 35, protein: 28, fat: 12, calories: 320},
        "Greek Yogurt": {carbs: 10, protein: 20, fat: 2, calories: 150},
        "Avocado Toast": {carbs: 25, protein: 6, fat: 15, calories: 280},
        "Protein Shake": {carbs: 8, protein: 40, fat: 2, calories: 220},
        "Quinoa Salad": {carbs: 40, protein: 10, fat: 8, calories: 300},
        "Egg Omelette": {carbs: 2, protein: 18, fat: 14, calories: 210}
    };
    const m = meals[meal];
    document.getElementById('meal_name').value = meal;
    document.getElementById('carbs').value = m.carbs;
    document.getElementById('protein').value = m.protein;
    document.getElementById('fat').value = m.fat;
    document.getElementById('calories').value = m.calories;
}
