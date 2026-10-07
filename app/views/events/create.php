<?php require "app/includes/header.php"; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="display-6 fw-semibold mb-0">Add New Event</h1>
    <a class="btn btn-outline-secondary" href="index.php?action=events">Back to events</a>
</div>

<?php

if (isset($_SESSION['errors'])) {

    foreach ($_SESSION['errors'] as $error) {

        echo "<div class=\"alert alert-danger\" role=\"alert\">" .
            htmlspecialchars($error) . "</div>";
    }

    unset($_SESSION['errors']);
}

?>

<form class="card border-0 shadow-sm p-4"
    action="index.php?action=store"
    method="POST"
>

    <label class="form-label">Event Name</label>

    <input
        class="form-control mb-3"
        type="text"
        name="event_name"
        maxlength="150"
        required
    >

    <label class="form-label">Event Date</label>

    <input
        class="form-control mb-3"
        type="date"
        name="event_date"
        required
    >

    <label class="form-label">Venue</label>

    <input
        class="form-control mb-3"
        type="text"
        name="venue"
        maxlength="150"
        required
    >

    <label class="form-label">Target Audience</label>

    <input
        class="form-control mb-3"
        type="text"
        name="target_audience"
        maxlength="150"
        required
    >

    <label class="form-label">Budget (RM)</label>

    <input
        class="form-control mb-3"
        type="number"
        name="budget"
        min="0"
        step="0.01"
        required
    >

    <label class="form-label">Description</label>

    <textarea
        class="form-control mb-3"
        name="description"
        rows="5"
    ></textarea>

    <label class="form-label">Status</label>

    <select class="form-select mb-4" name="status">

        <option value="Pending">
            Pending
        </option>

        <option value="Approved">
            Approved
        </option>

        <option value="Rejected">
            Rejected
        </option>

    </select>

    <button class="btn btn-primary" type="submit">Add Event</button>

</form>

<?php require "app/includes/footer.php"; ?>