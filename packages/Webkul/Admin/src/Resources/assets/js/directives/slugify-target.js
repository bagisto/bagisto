export default {
    mounted(el, binding) {
        let handler = function (e) {
            setTimeout(function () {
                binding.arg = binding.arg.replace(/([a-z]{2})_([a-z]{2})\[(.*?)\]/g, function(match, p1, p2, p3) {
                    return `${p1}_${p2.toUpperCase()}[${p3}]`;
                });
                
                var target = document.getElementById(binding.arg);

                target.value = e.target.value
                    .toString()
                    .toLowerCase()
                    .normalize('NFKD') // Normalize Unicode
                    .replace(/[\u0300-\u036f]/g, '') // Remove combining diacritical marks
                    .replace(/[^\p{L}\p{N}\s-]+/gu, '') // Remove all non-letter, non-number characters except spaces and dashes
                    .replace(/\s+/g, '-') // Replace spaces with dashes
                    .replace(/-+/g, '-') // Avoid multiple consecutive dashes
                    .replace(/^-+|-+$/g, ''); // Trim leading and trailing dashes
                
                if (binding.value) {
                    binding.value({
                        [binding.arg]: target.value
                    }, false);    
                }
            }, 100);
        };

        el.addEventListener('input', handler);
    }
}
