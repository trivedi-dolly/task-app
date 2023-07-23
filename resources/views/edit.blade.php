<!DOCTYPE html>
<html>
<head>
    <title>Edit Task</title>
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
    </style>
</head>
<body>
    <form action="/update/{{ $user->id }}" method="POST" enctype="multipart/form-data">
        @csrf
        <label for="name">Name:</label>
        <input type="text" name="name" required value="{{ $user->name }}">

        <label for="email">Email:</label>
        <input type="email" name="email" required value="{{ $user->email }}">

        <label for="phone">Phone:</label>
        <input type="text" name="phone" required value="{{ $user->phone }}">

        <label for="gender">Gender:</label>
        <input type="radio" name="gender" required value="{{ $user->gender }}" checked>Male
        <input type="radio" name="gender" required value="{{ $user->gender }}">Female

        <label for="education">Education:</label>
        <select name="education_id">
            <option value="{{ $user->education_id }}">{{ $user->education->degree_name }}</option>
            @foreach ($educations as $edu)
            <option value="{{ $edu->id }}">{{ $edu->degree_name }}</option>
            @endforeach
        </select>

        <label for="hobby">Hobbies:</label>
        <input type="checkbox" name="hobbies[]" value="Singing">Singing
        <input type="checkbox" name="hobbies[]" value="Dancing">Dancing
        <input type="checkbox" name="hobbies[]" value="Travelling">Travelling


        <label for="experience">Experience:</label>
        <div id="add_experience">
            <input type="text" name="experience[]" class="experience">
        </div>
        <a id="add_more">Add</a>
        <div class="append_here"></div><br>

        <label for="image">Picture:</label>
        <input type="file" name="image" accept="image/png, image/jpeg" >
        @if(isset($user) && !empty($user->image))
        <img src="{{ asset($user->image) }}" border="0" width="40" height="40" class="img-rounded"
                            align="center" />
        @endif

        <label for="message">Message:</label>
        <textarea name="message" rows="5" cols="18" value="{{ $user->message }}"></textarea>

        <button type="submit">Submit</button>
    </form>
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
    </script>
</body>
</html>
