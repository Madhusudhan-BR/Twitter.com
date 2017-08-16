
<footer class="footer">
    <div class="container">
        &copy;Madhusudhan.B.R, 2017.
    </div>

</footer>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalLabel">Login</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger" id="loginAlert"></div>

                <form>
                    <div class="form-group">
                        <label for="InputEmail">Email address</label>
                        <input type="email" class="form-control" id="email" aria-describedby="emailHelp" placeholder="Enter email">
                        <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
                    </div>
                    <div class="form-group">
                        <label for="InputPassword">Password</label>
                        <input type="password" class="form-control" id="password" placeholder="Password">
                        <input type="hidden" id="loginActive" value="1">
                    </div>
                </form>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="toggleLogin" >Signup</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="loginSignupButton">Login</button>
            </div>
        </div>
    </div>
</div>

<!-- jQuery first, then Tether, then Bootstrap JS. -->
<script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
<script
        src="https://code.jquery.com/jquery-3.2.1.js"
        integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE="
        crossorigin="anonymous"></script>
<script type="text/javascript">

    $("#tweetSuccess").hide();
    $("#tweetFailure").hide();

    $("#toggleLogin").click(function(){
        if($("#loginActive").val() == 1){
            $("#loginActive").val("0");
            $("#ModalLabel").html("Sign up!");
            $("#loginSignupButton").html("SignUp");
            $("#toggleLogin").html("Login");
        } else {
            $("#loginActive").val("1");
            $("#ModalLabel").html("Login!");
            $("#loginSignupButton").html("Login");
            $("#toggleLogin").html("Sign up");
        }
    })

    $("#loginSignupButton").click(function() {
        $.ajax({
            type: "POST",
            url: "actions.php?action=loginSignup",
            data: "email=" + $("#email").val() + "&password=" + $("#password").val() + "&loginActive=" + $("#loginActive").val() ,
            success: function(result) {
                if(result == "1") {
                    window.location.assign("http://madhusudhanbr.com.stackstaging.com/Twitter");
                }
                else {

                    $("#loginAlert").html(result).show();
                }
            }
        })
    })

    $(".toggleFollow").click(function() {

        var id = $(this).attr("data-userId");

        $.ajax({
            type: "POST",
            url: "actions.php?action=toggleFollow",
            data: "userId=" + $(this).attr("data-userId")  ,
            success: function(result) {

                if(result == "1") {
                    //user unfollowed

                    $("a[data-userId='" + id  +"']").html("Follow");

                } else {
                    //user followed

                    $("a[data-userId='" + id  + "']").html("Unfollow");
                }
            }
        })
    })

    $("#tweetButton").click(function(){


        $.ajax({
            type: "POST",
            url: "actions.php?action=postTweet",
            data: "tweetContent=" + $("#tweetContent").val()  ,
            success: function(result) {

                if(result == "1") {
                    $("#tweetSuccess").show();
                    $("#tweetFailure").hide();
                } else if(result != "") {
                    $("#tweetSuccess").hide();
                    $("#tweetFailure").html(result).show();
                }

            }
        })

    })

</script>

</body>
</html>