<script>
    window.onload = function() {
        let script = document.createElement('script');

        script.src = '{{ $clientEndPoint }}?render={{ $siteKey }}';

        script.onload = function() {
            grecaptcha.enterprise.ready(function() {
                const forms = document.querySelectorAll('form');
                
                forms.forEach(function(form) {
                    const tokenField = form.querySelector('#recaptcha-token');
                    
                    if (tokenField) {
                        form.addEventListener('submit', function(e) {
                            if (tokenField.value) {
                                return true;
                            }
                            
                            e.preventDefault();
                            
                            grecaptcha.enterprise.execute('{{ $siteKey }}', { action: 'submit' })
                                .then(function(token) {
                                    tokenField.value = token;
                                    form.submit();
                                });
                        });
                        
                        grecaptcha.enterprise.execute('{{ $siteKey }}', { action: 'submit' })
                            .then(function(token) {
                                tokenField.value = token;
                            });
                    }
                });
            });
        };

        document.body.appendChild(script);
    };
</script>
