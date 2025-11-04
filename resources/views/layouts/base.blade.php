<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('assets/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('assets/css/style2.css') }}"> --}}
    <link rel="stylesheet" href="{{ URL::asset('assets/bootstrap/font/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/datatables/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/datatables/datatables.responsive.min.css') }}">


    <title>
        @yield('title', 'Gestion Scolaire')
    </title>
    @yield('css')
</head>

<body>
    @yield('base')

    <script src="{{ asset('assets/chartjs/chart.min.js') }}"></script>
    <script src="{{ asset('assets/jquery/jquery.min.js') }}"></script>
    <script src="{{ URL::asset('assets/datatables/datatables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/datatables/datatables.responsive.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.menu-item').each(function() {
                $(`#${$(this).attr('data-item')}`).addClass('menu-item-to-slide')
                $(this).click(function() {
                    $(`#${$(this).attr('data-item')}`).slideToggle()
                    $(`.menu-item-to-slide:not(#${$(this).attr('data-item')})`).slideUp()
                })
            })

            $('.more-icon').each(function() {
                $(this).click(function() {
                    $('.dropdown-items:not(#' + $(this).attr('data-target') + ')').hide()
                    $(`#${$(this).attr('data-target')}`).toggle()
                })
            })
        })


        // const sidebar = document.querySelector('.sidebar');
        // const toggleButton = document.querySelector('#sidebarToggle');

        // toggleButton.addEventListener('click', () => {
        //     sidebar.classList.toggle('collapsed');
        // });

    </script>



    @yield('js')
</body>


</html>
