
<script>
      document.addEventListener('DOMContentLoaded', function() {
            const toggleBtns = document.querySelectorAll('.toggle-btn');

            toggleBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const targetId = this.id.replace('Btn', 'List');
                    const targetList = document.getElementById(targetId);

                    targetList.classList.toggle('show');

                    toggleBtns.forEach(otherBtn => {
                        if (otherBtn !== btn) {
                            const otherId = otherBtn.id.replace('Btn', 'List');
                            const otherList = document.getElementById(otherId);
                            otherList.classList.remove('show');
                        }
                    });
                });
            });
        });
        </script>