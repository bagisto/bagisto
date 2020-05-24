<script>
    export default {
        bind(el, binding, vnode) {
            el.addEventListener('click', function(e) {
                e.preventDefault();

                var message = "Are your sure you want to perform this action ?";

                if(binding.value && binding.value != '')
                    message = binding.value;

                if (confirm(message)) {
                    window.location.href = el.href;
                }
            });
        }
    }
</script>