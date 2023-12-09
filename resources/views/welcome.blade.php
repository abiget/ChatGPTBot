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
        <div class="card p-md-5 p-3 my-3">
            <div class="card-title text-center my-3">
                <h1 class="text-uppercase">ChatGPt 3.5 Turbo Integration</h1>
            </div>
            <div class="row form-floating  mx-md-5 px-md-5">
                <div class="col-md mx-md-2 my-4">
                    <textarea type="text" class="form-control" placeholder="Leave a your question here" id="question_from_user"
                        rows="2" onkeypress="getApiResponse(event)"></textarea>
                </div>
                <div class="col-md d-md-flex align-items-center d-flex justify-content-center">
                    <button type="button" class="btn btn-primary" onclick="getApiResponse(event)"
                        id="submit-button">Submit Your Questions
                    </button>
                </div>
            </div>

            <div class="jumbotron bg-light my-4 mx-md-5 px-md-5">
                <div class="form-floating">
                    <p class="text-center" id="question_box"></p>
                </div>
                <div>
                    <p class="text-justify p-md-4 p-2" id="response-answer"></p>
                </div>
            </div>

            <div class="d-flex justify-content-center">
                <button class="btn btn-primary mx-5" id="abort_button" style="display: none;">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                        <circle cx="12" cy="12" r="10" fill="red" />
                        <rect x="9" y="6" width="6" height="12" fill="white" />
                    </svg>Stop</button>
            </div>

        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script>
        const result = document.getElementById("response-answer");
        var source;

        function showElements() {
            $("#question_from_user").prop('disabled', false);
            $("#submit-button").prop('disabled', false);
            $("#abort_button").css('display', 'none');
        }

        function getApiResponse(event) {
            if (event.target.type == 'textarea') {
                if (event.keyCode == 13) {
                    const question = document.getElementById("question_from_user");
                    if (question.value === "") return;
                    const question_box = document.getElementById("question_box");
                    question_box.innerText = question.value;
                    result.innerHTML = "";

                    $("#question_from_user").prop('disabled', true);
                    $("#submit-button").prop('disabled', true);
                    $("#abort_button").css('display', 'block');

                    const queryQuestion = encodeURIComponent(question.value);
                    question.value = "";
                    source = new EventSource("/chatbot?question=" + queryQuestion);
                    source.addEventListener("update", function(event) {
                        if (event.data === "<end>") {
                            source.close();
                            console.log('here');
                            return;
                        }

                        result.innerHTML += event.data;
                    });

                    // source.addEventListener("error", function(event) {
                    //     // Handle error event
                    //     console.log('here error');
                    //     showElements();
                    // });

                    source.addEventListener("close", function(event) {
                        // Handle close event
                        console.log('here close');

                        showElements();

                    });
                }
            } else {
                const question = document.getElementById("question_from_user");
                if (question.value === "") return;
                const question_box = document.getElementById("question_box");
                question_box.innerText = question.value;
                result.innerHTML = "";

                $("#question_from_user").prop('disabled', true);
                $("#submit-button").prop('disabled', true);
                $("#abort_button").css('display', 'block');

                const queryQuestion = encodeURIComponent(question.value);
                question.value = "";
                source = new EventSource("/chatbot?question=" + queryQuestion);
                source.addEventListener("update", function(event) {
                    if (event.data === "<end>") {
                        source.close();
                        return;
                    }
                    result.innerHTML += event.data;
                });

                source.addEventListener("error", function(event) {
                    // Handle error event
                    showElements();
                });

                source.addEventListener("close", function(event) {
                    // Handle close event
                    showElements();

                });

            }
        }

        document.getElementById("abort_button").addEventListener("click", function() {
            source.close();
            $("#abort_button").css('display', 'none');
            $("#question_from_user").prop('disabled', false);
            $("#submit-button").prop('disabled', false);
            console.log("EventSource connection aborted.");
        });
    </script>
</body>

</html>
