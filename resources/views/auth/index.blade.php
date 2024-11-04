<body style="padding:1rem;margin-bottom:6rem">

    @if(session('success'))
    <div class="alert alert-success" role="alert">
        {{ session('success') }}
    </div>
    @elseif(session('error'))
    <div class="alert alert-danger" role="alert">
        {{ session('error') }}
    </div>
    @endif

    <h1>{{ Auth::user()->name }}</h1>

    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" style="
            background-color: #FF2D20;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        ">
            Logout
        </button>
    </form>

    <input type="text" id="search" placeholder="Search for books..." style="width: 100%; padding: 10px; margin-bottom: 20px;">
    <div id="book-results"></div>



    <h1>My Books</h1>

  


    @if($books->isEmpty())
    <p style="text-align: center; font-size: 1.2em; color: #888; margin-top: 20px;">
        No books found. Click "Add New" to create your first book!
    </p>
    @else


    <div class="book-list-header">
        <h2>Title</h2>
        <h2>Author</h2>
        <h2>Publish Date</h2>
        <h2>Description</h2>
        <h2>Action</h2>
    </div>

    @foreach($books as $book)
    <div class="book-list-row">
        <div>{{ $book->title }}</div>
        <div>{{ $book->author }}</div>
        <div>{{ \Carbon\Carbon::parse($book->publish_date)->format('F j, Y') }}</div>
        <div>{{ $book->description }}</div>
        <div class="action-buttons">
            <a href="{{ route('booksEdit', $book->id) }}" class="edit-button">Edit</a>
            <form action="{{ route('booksDestroy', $book->id) }}" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="delete-button">Delete</button>
            </form>
        </div>
    </div>
    @endforeach

    <div class="pagination-container" style="margin-top: 20px;">
        {{ $books->links('vendor.pagination.simple-default') }}
    </div>
    @endif

    <a href="{{ route('booksCreate') }}" class="add-new-button">Add New</a>

    <style>
        .book-list-header,
        .book-list-row {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr 2fr 1fr;
            gap: 10px;
            padding: 10px;
            border-bottom: 1px solid #ccc;
        }

        .book-list-header {
            font-weight: bold;
            background-color: #f8f9fa;
        }

        .book-list-row {
            align-items: center;
        }

        .action-buttons {
            display: flex;
            gap: 5px;
        }

        .edit-button{
            height:17px;
        }
        .edit-button,
        .delete-button {
            background-color: #007bff;
            color: white;
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
            border: none;
            cursor: pointer;
        }

        .delete-button {
            background-color: #dc3545;
        }

        .add-new-button {
            display: inline-block;
            background-color: #FF2D20;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            transition: background-color 0.3s;
            margin-top: 10px;
        }

        #book-results {
            margin-top: 20px;
        }
    </style>


    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script>
        $(document).ready(function() {
            $('#search').on('keyup', function() {
                let query = $(this).val();


                if (query.length > 0) {
                    $.ajax({
                        url: "{{ route('booksSearch') }}",
                        method: 'GET',
                        data: {
                            query: query
                        },
                        success: function(data) {
                            let results = '';

                            if (data.length > 0) {

                                $.each(data, function(index, book) {
                                    results += `
                                    <div style="border: 1px solid #ccc; padding: 10px; margin-bottom: 10px; border-radius: 5px;">
                                        <h3>${book.title}</h3>
                                        <p><strong>Author:</strong> ${book.author}</p>
                                        <p><strong>Publish Date:</strong> ${book.publish_date}</p>
                                        <p><strong>Description :</strong>${book.description}</p>
                                    </div>
                                `;
                                });
                            } else {
                                results = '<p>No books found</p>';
                            }

                            $('#book-results').html(results);
                        },
                        error: function(xhr) {
                            console.error(xhr);
                        }
                    });
                } else {
                    $('#book-results').empty();
                }
            });
        });
    </script>


</body>