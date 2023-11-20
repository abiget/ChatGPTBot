<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ChatGPTBot</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>

</head>

<body>
    <div class="container">
        <div class="card p-5 my-3">
            <div class="form-floating">
                <textarea class="form-control" placeholder="Leave a your question here" id="question_from_user" style="height: 100px"
                    rows="5" cols="5"></textarea>
                <label for="question_from_user">Questions</label>
            </div>
            <div class="mx-4 my-4 d-flex justify-content-center">
                <button type="button" class="btn btn-primary" onclick="getApiResponse(this)">Submit Your Questions
                </button>
            </div>

            <div class="jumbotron bg-light">
                <h2 class="text-center">Response</h2>
                <div>
                    <p class="text-justify p-4" id="response-answer"></p>
                </div>
            </div>

            {{-- <a class="btn btn-primary m-5" href="{{ route('chatbot') }}">ChatGPTBot</a> --}}

        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script>
        function getApiResponse(element) {

            $('#response-answer').text('');
            var question = $('#question_from_user').val();

            $.ajax({
                type: "GET",
                url: "{{ route('chatbot') }}",
                data: {
                    'question': question
                },
                dataType: "json",
                success: function(response) {
                    console.log('success');
                    $('#response-answer').text(response.message);

                },
                error: function(error) {
                    console.log('error');
                }
            });
        }
    </script>
</body>

</html>
