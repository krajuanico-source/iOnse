<!-- resources/views/content/welfare/toastrwelfare/toastrwel.blade.php -->

@if (session('success'))
    <script>
        $(function() {
            toastr.success("{{ session('success') }}");
        });
    </script>
@endif

@if (session('error'))
    <script>
        $(function() {
            toastr.error("{{ session('error') }}");
        });
    </script>
@endif

@if (session('warning'))
    <script>
        $(function() {
            toastr.warning("{{ session('warning') }}");
        });
    </script>
@endif

@if (session('info'))
    <script>
        $(function() {
            toastr.info("{{ session('info') }}");
        });
    </script>
@endif