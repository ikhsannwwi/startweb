@extends('administrator.authentication.main')

@section('content')
    <section class="section">
        <div class="container mt-5">
            <div class="row">
                <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
                    @include('administrator.authentication.header')


                    <div class="card card-primary">
                        <div class="card-header">
                            <h4>Reset Password</h4>
                        </div>

                        <div class="card-body">
                            <p class="text-muted">We will send a link to reset your password</p>
                            <form action="{{ route('admin.profile.password.update', $resetPassword->token) }}" method="POST"
                                id="form" novalidate="" data-parsley-validate>
                                @csrf
                                @method('POST')

                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input id="email" type="email" placeholder="Masukan Email" class="form-control"
                                        autocomplete="off" name="email" tabindex="1" data-parsley-required="true"
                                        data-parsley-type="email" data-parsley-trigger="change"
                                        data-parsley-error-message="Masukan alamat email yang valid." autofocus>
                                    <div class="" style="color: #dc3545" id="accessErrorEmail"></div>
                                </div>

                                <div class="form-group">
                                    <label for="passwordField">New Password</label>
                                    <input id="passwordField" type="password" class="form-control pwstrength"
                                        data-indicator="pwindicator" name="password" tabindex="2"
                                        placeholder="Masukan Password" autocomplete="off" data-parsley-required="true">
                                    <div id="pwindicator" class="pwindicator">
                                        <div class="bar"></div>
                                        <div class="label"></div>
                                    </div>
                                    <div class="text-sm" style="color: #dc3545" id="accessErrorPasssword"></div>
                                    
                                  </div>

                                <div class="form-group">
                                    <label for="konfirmasiPasswordField">Confirm Password</label>
                                    <input id="konfirmasiPasswordField" type="password" class="form-control" tabindex="2"
                                        placeholder="Masukan Konfirmasi Password" name="konfirmasi_password"
                                        autocomplete="off" data-parsley-required="true">
                                        <div class="text-sm" style="color: #dc3545" id="accessErrorKonfirmasiPasssword"></div>
                                </div>

                                <div class="form-group">
                                    <button type="submit" id="formSubmit" class="btn btn-primary btn-lg btn-block"
                                        tabindex="4">
                                        <span class="indicator-label">Submit</span>
                                        <span class="indicator-progress" style="display: none;">
                                            Tunggu Sebentar...
                                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                        </span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    @include('administrator.authentication.footer')
                </div>
            </div>
        </div>
    </section>
@endsection

@push('js')
    <script type="text/javascript">
        $(document).ready(function() {
            //validate parsley form
            const form = document.getElementById("form");
            const validator = $(form).parsley();

            const submitButton = document.getElementById("formSubmit");


            submitButton.addEventListener("click", async function(e) {
                e.preventDefault();
                indicatorBlock();

                const passwordField = $('#passwordField').val().trim();

                // Perform remote validation
                const remoteValidationResultEmail = await validateRemoteEmail();
                const email = $("#email");
                const accessErrorEmail = $("#accessErrorEmail");
                if (!remoteValidationResultEmail.valid) {
                    // Remote validation failed, display the error message
                    accessErrorEmail.addClass('invalid-feedback');
                    email.addClass('is-invalid');

                    accessErrorEmail.text(remoteValidationResultEmail
                        .errorMessage); // Set the error message from the response
                    indicatorNone();

                    return;
                } else {
                    accessErrorEmail.removeClass('invalid-feedback');
                    email.removeClass('is-invalid');
                    accessErrorEmail.text('');
                }

                if (passwordField !== '') {
                    if (!validatePasswordConfirmation()) {
                        return;
                    }
                }

                // Validate the form using Parsley
                if ($(form).parsley().validate()) {
                    indicatorSubmit();
                    form.submit();
                } else {
                    // Handle validation errors
                    const validationErrors = [];
                    $(form).find(':input').each(function() {
                        const field = $(this);
                        if (!field.parsley().isValid()) {
                            indicatorNone();
                            const attrName = field.attr('name');
                            const errorMessage = field.parsley().getErrorsMessages().join(
                                ', ');
                            validationErrors.push(attrName + ': ' + errorMessage);
                        }
                    });
                    console.log("Validation errors:", validationErrors.join('\n'));
                }
            });

            function indicatorSubmit() {
                submitButton.querySelector('.indicator-label').style.display =
                    'inline-block';
                submitButton.querySelector('.indicator-progress').style.display =
                    'none';
            }

            function indicatorNone() {
                submitButton.querySelector('.indicator-label').style.display =
                    'inline-block';
                submitButton.querySelector('.indicator-progress').style.display =
                    'none';
                submitButton.disabled = false;
            }

            function indicatorBlock() {
                // Disable the submit button and show the "Please wait..." message
                submitButton.disabled = true;
                submitButton.querySelector('.indicator-label').style.display = 'none';
                submitButton.querySelector('.indicator-progress').style.display =
                    'inline-block';
            }

            $('#passwordField, #konfirmasiPasswordField').on('input', function() {
                validatePasswordConfirmation();
            });

            function validatePasswordConfirmation() {
                const passwordField = $('#passwordField');
                const accessErrorPassword = $("#accessErrorPasssword");
                const konfirmasiPasswordField = $('#konfirmasiPasswordField');
                const accessErrorKonfirmasiPassword = $("#accessErrorKonfirmasiPasssword");

                if (passwordField.val().length < 8) {
                    passwordField.addClass('is-invalid');
                    accessErrorPassword.text('Password harus memiliki setidaknya 8 karakter');
                      indicatorNone();
                    return false;
                } else if (passwordField.val() !== konfirmasiPasswordField.val()) {
                    passwordField.removeClass('is-invalid');
                    accessErrorPassword.text('');
                    konfirmasiPasswordField.addClass('is-invalid');
                    accessErrorKonfirmasiPassword.text('Konfirmasi Password harus sama dengan Password');
                      indicatorNone();
                    return false;
                } else {
                    passwordField.removeClass('is-invalid');
                    accessErrorPassword.text('');
                    konfirmasiPasswordField.removeClass('is-invalid');
                    accessErrorKonfirmasiPassword.text('');
                    return true;
                }
            }

            async function validateRemoteEmail() {
                const email = $('#email');
                const remoteValidationUrl = "{{ route('admin.login.checkEmail') }}";
                const csrfToken = "{{ csrf_token() }}";

                try {
                    const response = await $.ajax({
                        method: "POST",
                        url: remoteValidationUrl,
                        data: {
                            _token: csrfToken,
                            email: email.val()
                        }
                    });

                    // Assuming the response is JSON and contains a "valid" key
                    return {
                        valid: response.valid === true,
                        errorMessage: response.message
                    };
                } catch (error) {
                    console.error("Remote validation error:", error);
                    return {
                        valid: false,
                        errorMessage: "An error occurred during validation."
                    };
                }
            }
        });
    </script>
@endpush
