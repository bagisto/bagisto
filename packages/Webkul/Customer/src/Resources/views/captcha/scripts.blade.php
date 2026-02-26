<script>
    window.onload = function() {
        let script = document.createElement('script');

        script.src = '{{ $clientEndPoint }}';

        document.body.appendChild(script);
    };
</script>
