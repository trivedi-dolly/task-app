<!DOCTYPE html>
<html>

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Task</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        form {
            max-width: 400px;
            margin: 0 auto;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="email"],
        select,
        textarea {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="file"] {
            margin-bottom: 20px;
        }

        button[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button[type="submit"]:hover {
            background-color: #45a049;
        }

        .experience {
            display: block;
            margin-bottom: 10px;
        }

        .remove {
            margin-left: 10px;
            cursor: pointer;
        }

        .error {
            color: red;
        }
        
    </style>
</head>

<body>
    <form action="submit" method="POST" enctype="multipart/form-data">
        @csrf
        <label for="name">Name:</label>
        <input type="text" name="name">
        @error('name') <span class="error">{{ $message }}</span> @enderror

        <label for="email">Email:</label>
        <input type="email" name="email">
        @error('email') <span class="error">{{ $message }}</span> @enderror

        <label for="phone">Phone:</label>
        <input type="text" name="phone">
        @error('phone') <span class="error">{{ $message }}</span> @enderror

        <label for="gender">Gender:</label>
        <input type="radio" name="gender" value="male" checked>Male
        <input type="radio" name="gender" value="female">Female
        @error('gender') <span class="error">{{ $message }}</span> @enderror

        <label for="education">Education:</label>
        <select name="education_id">
            <option>Select Education</option>
            @foreach ($educations as $edu)
            <option value="{{ $edu->id }}">{{ $edu->degree_name }}</option>
            @endforeach
        </select>
        @error('education_id') <span class="error">{{ $message }}</span> @enderror

        <label for="hobby">Hobbies:</label>
        <input type="checkbox" name="hobbies[]" value="Singing">Singing
        <input type="checkbox" name="hobbies[]" value="Dancing">Dancing
        <input type="checkbox" name="hobbies[]" value="Travelling">Travelling
        @error('hobbies') <span class="error">{{ $message }}</span> @enderror


        <label for="experience">Experience:</label>
        <div id="add_experience">
            <input type="text" name="experience[]" class="experience">
        </div>
        <a id="add_more" href="#">Add</a>
        <div class="append_here"></div><br>
        @error('experience') <span class="error">{{ $message }}</span> @enderror

        <label for="image">Picture:</label>
        <input type="file" name="image" required accept="image/png, image/jpeg">
        @error('image') <span class="error">{{ $message }}</span> @enderror

        <label for="message">Message:</label>
        <textarea name="message" rows="5" cols="18"></textarea>
        @error('message') <span class="error">{{ $message }}</span> @enderror

        <button type="submit">Submit</button>
    </form>
    <br />
    <div style="width: 40%">
        <input type="text" id="searchInput" placeholder="Search...">
        <ul id="searchResults"></ul>
    </div>
    <div id="table-container">
        <table border="1">
            <thead>
                <th>Sr.ID</th>
                <th>Name</th>
                <th>Hobby</th>
                <th>Email</th>
                <th>Picture</th>
                <th>Action</th>
            </thead>
            <tbody id="records-container">
                @foreach ($details as $data)
                <tr>
                    <td>{{ $data->id }}</td>
                    <td>{{ $data->name }}</td>
                    <td>{{ $data->hobbies }}</td>
                    <td>{{ $data->email }}</td>
                    <td>@if ($data->image)
                        <img src="{{ asset($data->image) }}" border="0" width="40" height="40" class="img-rounded"
                            align="center" />
                        @else NA @endif
                    </td>
                    <td><a href="edit/{{ $data->id }}">Edit</a> |
                        <a href="#" class="delete-btn" data-user-id="{{ $data->id }}">Delete</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <button id="show-more-btn" data-current-page="1">Show More</button>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#add_more').click(function () {
                const clonedExperience = $('#add_experience').clone();
                const removeButton = $('<button class="remove">Remove</button>');

                clonedExperience.appendTo('.append_here').addClass('experience');
                removeButton.appendTo(clonedExperience);
            });

            $(document).on('click', '.remove', function (e) {
                e.preventDefault();
                $(this).closest('.experience').remove();
            });
        });
        $('#show-more-btn').on('click', function() {
            const currentPage = parseInt($(this).data('current-page'));
            const APP_URL = "{{ env('APP_URL') }}";

        $.ajax({
            url: '/get-records', 
            type: 'GET',
            data: {
                page: currentPage + 1, 
            },
            success: function(response) {
                if (response && response.length > 0) {
                    const $tbody = $('#records-container');
                    $.each(response, function(index, data) {
                        const newRow = `
                            <tr>
                                <td>${data.id}</td>
                                <td>${data.name}</td>
                                <td>${data.hobbies}</td>
                                <td>${data.email}</td>
                                <td>${data.image? `<img src="${APP_URL}/${data.image}" border="0" width="40" height="40" class="img-rounded" align="center" />`: 'NA' }</td>
                                <td>
                                    <a href="edit/${data.id}">Edit</a> |
                                    <a href="data/${data.id}">Delete</a>
                                </td>
                            </tr>
                        `;
                        $tbody.append(newRow);
                    });

                    $('#show-more-btn').data('current-page', currentPage + 1);
                } else {
                    $('#show-more-btn').hide();
                }
            },
            error: function(error) {
                console.log('Error fetching records:', error);
            }
        });
    });
    $('#searchInput').on('keyup', function () {
            const searchTerm = $(this).val().trim();
            const APP_URL = "{{ env('APP_URL') }}";
            $.ajax({
                url: '/search',
                type: 'GET',
                data: { q: searchTerm },
                dataType: 'json',
                success: function (response) {
                    $('#table-container').empty();

                    const newTable = $('<table border="1">').appendTo('#table-container');
                    const tableHeader = $('<thead>').appendTo(newTable);
                    const tableBody = $('<tbody>').appendTo(newTable);

                    tableHeader.append('<th>Sr.ID</th>');
                    tableHeader.append('<th>Name</th>');
                    tableHeader.append('<th>Hobby</th>');
                    tableHeader.append('<th>Email</th>');
                    tableHeader.append('<th>Picture</th>');
                    tableHeader.append('<th>Action</th>');

                    response.forEach(function (user) {
                        const newRow = `
                            <tr>
                                <td>${user.id}</td>
                                <td>${user.name}</td>
                                <td>${user.hobbies}</td>
                                <td>${user.email}</td>
                                <td>${user.image ? `<img src="${APP_URL}/${user.image}" border="0" width="40" height="40" class="img-rounded" align="center" />` : 'NA'}</td>
                                <td>
                                    <a href="edit/${user.id}">Edit</a> |
                                    <a href="data/${user.id}">Delete</a>
                                </td>
                            </tr>`;
                        tableBody.append(newRow);
                    });
                },
                error: function (error) {
                    console.log('Error fetching search results:', error);
                }
            });
        });
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
         $('body').on('click', '.delete-btn', function (event) {
            event.preventDefault();
                const userId = $(this).data('user-id');

                const deleteBtn = $(this);

                $.ajax({
                    url: '/delete/' + userId,
                    type: 'GET',
                    dataType: 'json',
                    success: function (response) {
                    alert(response.message);
                    deleteBtn.closest('tr').remove();
                    },
                    error: function (error) {
                    alert( error.responseJSON.message);
                }
                });
                });
    </script>
</body>

</html>