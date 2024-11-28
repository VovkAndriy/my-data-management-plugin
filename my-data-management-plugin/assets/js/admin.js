jQuery(document).ready(function($) {

    // console.log( ajaxurl );
    // console.log( '4234234' );
    // Function to load data via AJAX
    function load_data(page, search_query = '') {
        $.ajax({
            url: ajaxurl, // URL for handling the AJAX request, set via wp_localize_script
            type: 'POST',
            // contentType: "application/json",
            // dataType: 'json',
            data: {
                action: 'load_data', // The action hook that will be triggered on the server
                page: page, // Send current page number for pagination
                search: search_query // Send search query for filtering
            },
            success: function(response) {

                var data = response.results;
                var table_body = $('#data-table tbody');
                var pagination = $('#pagination');

                if (response.total_pages) {
                    table_body.empty(); // Clear the table before adding new data
                    pagination.empty(); // Clear pagination

                   // Add new rows to the table
                    $.each(data, function(index, row) {
                        table_body.append(
                            '<tr>' +
                            '<td>' + row.id + '</td>' + // ID column
                            '<td>' + row.name + '</td>' + // Name column
                            '<td>' + row.email + '</td>' + // Email column
                            '<td>' + row.date_added + '</td>' + // Date added column
                            '</tr>'
                        );
                    });

                    // Generate pagination links
                    for (let i = 1; i <= response.total_pages; i++) {
                        pagination.append(
                            '<a href="#" class="pagination-link ' + (i == page ? '_active' : '') + '" data-page="' + i + '">' + i + '</a> '
                        );
                    }
                } else {
                    table_body.empty(); // Clear the table before adding new data
                    pagination.empty(); // Clear pagination
                    $('#data-table tbody').append('<tr><td colspan="4" align="center">'+ response.message +'</td></tr>');
                }
            },
            error: function() {
                alert('Error loading data.');
            }
        });
    }

    // Load data when the page is loaded
    load_data(1); // Load the first page of data

    // Handle pagination link clicks
    $(document).on('click', '.pagination-link', function(e) {
        e.preventDefault(); // Prevent default link behavior
        var page = $(this).data('page');
        var search_query = $('#search-name').val(); // Get current search query
        load_data(page, search_query); // Load data for the clicked page
    });

    // Trigger AJAX load on search field input
    $('#search-name').on('keyup', function () {
        var search_query = $(this).val(); // Get the search query
        load_data(1, search_query); // Always load the first page when filtering
    });




    // Handle form submission to add a new record
    $('#new-record-form').on('submit', function(e) {
        e.preventDefault(); // Prevent the default form submission (page reload)

        var name = $('#name').val(); // Get the value of the name input
        var email = $('#email').val(); // Get the value of the email input


         // Basic client-side validation for name and email
        if (name == '' || email == '') {
            alert('Please fill in both fields!'); // Show alert if any field is empty
            return;
        }

        $.ajax({
            url: ajaxurl, // The URL where the request will be sent, defined via wp_localize_script
            type: 'POST', // The HTTP method to be used
            data: {
                action: 'add_new_record', // The action hook to be triggered on the server
                name: name, // The name input value
                email: email // The email input value
            },
            success: function(response) {
                if (response.success) {
                    // // If the response is successful, add the new record to the table
                    // $('#data-table tbody').append(
                    //     '<tr>' +
                    //     '<td>' + response.data.id + '</td>' + // Display the ID
                    //     '<td>' + response.data.name + '</td>' + // Display the Name
                    //     '<td>' + response.data.email + '</td>' + // Display the Email
                    //     '<td>' + response.data.date_added + '</td>' + // Display the Date Added
                    //     '</tr>'
                    // );

                    load_data(1);
                    // Reset the form after successful submission
                    $('#new-record-form')[0].reset();
                } else {
                    alert('There was an error adding the record.'); // Show an error if something went wrong
                }
            },
            error: function() {
                alert('Error connecting to the server.'); // Show an error if the AJAX request fails
            }
        });

    });

});
