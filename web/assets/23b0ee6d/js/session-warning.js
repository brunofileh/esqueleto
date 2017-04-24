(function ($) {

    var die = function ($e) {
        swal({
            title: "Atenção",
            text: "Sua sessão expirou. Clique OK para realizar o login novamente.",
            type: "error",
            allowEscapeKey: false,
            allowOutsideClick: false
        }, function() {
            methods._logout.apply($e);
        });
    }

    $.fn.sessionWarning = function (method) {
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        } else {
            $.error('Method ' + method + ' does not exist');
            return false;
        }
    };

    var defaultSettings = {
        cookieUser: '__swuid',
        cookieTimeout: '__swto',
        cookieTimeoutAbsolute: '__swato',
        extendUrl: null,
        userId: null,
        warnBefore: null,
        logoutUrl: null,
        message: null
    };
    var settings = {};

    var methods = {
        init: function (options) {
            return this.each(function () {
                var $e = $(this);
                settings = $.extend({}, defaultSettings, $e.data(), options || {});
                settings.warnBefore = parseInt(settings.warnBefore);

                // checagem inicial
				setTimeout(function () {
                    methods._watch.apply($e);
                }, 1000);
				
				// Check every 10 seconds
                setInterval(function () {
                    methods._watch.apply($e);
                }, 10000);

                methods._onContinueClick.apply($e);
            });
        },

        /**
         * On continue button click renews user session
         * @private
         */
        _onContinueClick: function () {
            var $e = $(this);
            $e.on('click', '.continue', function () {
//                var $dialog = $e.find('.modal-dialog');
                // $dialog.addClass('loading');
                $('span.message').html('Aguarde...');
                $('button.continue').hide();
                $.ajax({
                    method: "GET",
                    url: settings.extendUrl,
                    success: function (data) {
                        if (data.success) {
                            $e.modal('hide');
                        } else {
                            location.reload();
                        }
                    },
                    error: function () {
                        $('button.continue').show();
                        $e.hide();
                    },
                    complete: function () {
                        // $dialog.removeClass('loading');
                        $('button.continue').show();
                    }
                });
            });
        },

        /**
         * Logs out user
         * @private
         */
        _logout: function() {
            if(settings.logoutUrl) {
                window.location = settings.logoutUrl;
            } else {
                location.reload();
            }
        },

        /**
         * Checks and shows warning if needed.
         * @private
         */
        _watch: function () {
            var $e = $(this),
                userId = Cookies.get(settings.cookieUser),
                timeout = Cookies.get(settings.cookieTimeout),
                absoluteTimeout = Cookies.get(settings.cookieTimeoutAbsolute);

            timeout = timeout ? parseInt(timeout) : null;
            absoluteTimeout = absoluteTimeout ? parseInt(absoluteTimeout) : null;

            // Checks whether user is logged in.
            if (!userId || !timeout || userId != settings.userId) {
                die($e);
                return;
            }

            // Current timestamp
            var timestamp = mgcode.helpers.time.getTimestamp();

            // Absolute timeout reached
            if(absoluteTimeout && (absoluteTimeout - timestamp) < 0) {
                $e.modal('hide');
                die($e);
                return;
            }

            // Timeout reached
            if(timeout && (timeout - timestamp) < 0) {
                $e.modal('hide');
                die($e);
                return;
            }

            // Calculate timestamp difference
            var difference = timeout - timestamp;
            
            var hours = Math.floor((difference / 3600) % 24);
                mins = Math.floor((difference / 60) % 60),
                pad = '00',
                strHours = (pad + hours).slice(-pad.length),
                strMins = (pad + (mins +1)).slice(-pad.length)
            ;

            var $sessionTimeout = $('#session-timeout');
            $sessionTimeout.html(strHours + ':' + strMins);
			$sessionTimeout.parents('li:first').css('background-color', '');
            if (mins < 10) {
                $sessionTimeout.parents('li:first').css('background-color', 'red');
            }

            // If modal is opened, close it.
            if (settings.warnBefore < difference) {
                $e.modal('hide');
                return;
            }

            // Format time and generate warning message
            var message = settings.message.replace('{time}', (mins +1) + ' minuto' + (mins>0 ? 's' : ''));

            // Show warning
            $e.find('.message').html(message);
            if (!$e.hasClass('in')) {
                $e.modal('show');
            }
        }
    };
})(jQuery);