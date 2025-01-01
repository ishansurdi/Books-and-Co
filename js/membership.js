document.addEventListener('DOMContentLoaded', function () {
    const planSelect = document.getElementById('membership_plan');
    const planIdInput = document.getElementById('membership_plan_id');
    const amountInput = document.getElementById('membership_amount');

    // Define numeric plan IDs and amounts
    const plans = {
        "Monthly": { id: 1, amount: 300 },  // Numeric Plan ID for Monthly
        "Half-Yearly": { id: 2, amount: 800 },  // Numeric Plan ID for Half-Yearly
        "Yearly": { id: 3, amount: 1000 }  // Numeric Plan ID for Yearly
    };

    // Update plan ID and amount when the user selects a membership plan
    planSelect.addEventListener('change', function () {
        const selectedPlan = planSelect.value;
        if (plans[selectedPlan]) {
            planIdInput.value = plans[selectedPlan].id;  // Numeric ID
            amountInput.value = `₹ ${plans[selectedPlan].amount.toFixed(2)}`;
        } else {
            planIdInput.value = '';
            amountInput.value = '';
        }
    });
});