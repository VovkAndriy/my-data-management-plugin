<div class="wrap">
    <!-- Title -->
    <h1>My Data Management</h1>
    <div id="search-form">
        <label for="search-name">Search by Name:</label>
        <input type="text" id="search-name" placeholder="Type a name...">
    </div>
    <table id="data-table" class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th>Id</th>
                <th>Name</th>
                <th>Email</th>
                <th>Date_added</th>
            </tr>
        </thead>
        <tbody></tbody> <!-- Data will be added here via AJAX -->
    </table>

    <!-- Pagination links will appear here -->
    <div id="pagination"></div>

    <!-- Form for adding new records -->
    <div id="add-record-form">
        <h3>Add a New Record</h3>
        <form id="new-record-form">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required placeholder="Enter name">
            
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required placeholder="Enter email">
            
            <button type="submit">Add Record</button>
        </form>
    </div>

</div>
