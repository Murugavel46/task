<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Book</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
</head>
<body style="background-color: #f0f0f0;">
    <div class="d-flex justify-content-center align-items-center vh-100">
        <div class="card shadow p-4" style="max-width: 45rem; width: 100%;">
            <h2 class="text-center mb-4">Edit Book</h2>
            <form id="editBookForm" action="{{ route('booksUpdate', $book->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="title" class="font-weight-bold">Book Title:</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $book->title) }}">
                </div>
                <div class="form-group">
                    <label for="author" class="font-weight-bold">Author Name:</label>
                    <input type="text" name="author" class="form-control" value="{{ old('author', $book->author) }}">
                </div>
                <div class="form-group">
                    <label for="publish_date" class="font-weight-bold">Publish Date:</label>
                    <input type="date" name="publish_date" class="form-control" value="{{ old('publish_date', $book->publish_date) }}">
                </div>
                <div class="form-group">
                    <label for="description" class="font-weight-bold">Description:</label>
                    <textarea name="description" class="form-control" rows="4">{{ old('description', $book->description) }}</textarea>
                </div>
                <button type="submit" class="btn btn-danger btn-block" style="margin-bottom: 1rem;">Update Book</button>
            </form>
            <a href="{{ url()->previous() }}" style="
                display: inline-block;
                width:5rem;
                background-color: #FF2D20;
                color: white;
                padding: 10px 20px;
                text-decoration: none;
                border-radius: 5px;
                font-size: 16px;
                transition: background-color 0.3s;
                margin-top: 10px;
            ">
    Back
</a>

        </div>
    </div>

    <script>
        $.validator.addMethod("lettersonly", function(value, element) {
            return this.optional(element) || /^[a-zA-Z\s]+$/.test(value);
        }, "This field can contain letters only.");

        $(document).ready(function() {
            $("#editBookForm").validate({
                rules: {
                    title: {
                        required: true,
                        minlength: 2,
                        lettersonly: true
                    },
                    author: {
                        required: true,
                        minlength: 2,
                        lettersonly: true
                    },
                    publish_date: {
                        required: true,
                        date: true
                    },
                    description: {
                        required: true,
                        minlength: 5
                    }
                },
                messages: {
                    title: {
                        required: "Please enter the book title",
                        minlength: "Book title must be at least 2 characters long"
                    },
                    author: {
                        required: "Please enter the author's name",
                        minlength: "Author name must be at least 2 characters long"
                    },
                    publish_date: {
                        required: "Please select a publish date",
                        date: "Please enter a valid date"
                    },
                    description: {
                        required: "Please enter a description",
                        minlength: "Description must be at least 5 characters long"
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
            });
        });
    </script>
</body>
</html>
