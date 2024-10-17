<header>
    <div class="main-header">
        <div class="left-side d-flex align-items-center">
            <button class="btn btn-hide-aside" id="btn-hide-aside"><i class="fas fa-align-left"></i></button>
            <div class="mobile-icon">
                <div class="menu-opener">
                    <div class="menu-opener-inner"></div>
                </div>
            </div>
            <div class="logo">
                <img src="{{ $config['first_logo'] }}" alt="Logo One">
                @if (!empty($config['second_logo']) && $config['second_logo'] != 'images/logomsonly.png')
                    <img src="{{ $config['second_logo'] }}" class="ms-1" alt="Logo One">
                @endif
                <div class="text">
                    <h1>{{ $config['first_title'] }}</h1>
                    <h1>{{ $config['second_title'] }}</h1>
                </div>
            </div>
            <button class="mobile-icon-right">
                <i class="fas fa-ellipsis-v"></i>
            </button>
        </div>
        <div class="menu">
            <ul>
                <li><a href="javascript:void(0);" onclick="window.document.getElementById('formLogout').submit();"><i class="fa-solid fa-right-from-bracket"></i> Log Out</a></li>
            </ul>
        </div>
    </div>
</header>
<form id="formLogout" class="d-none" action="/parent/logout" method="POST">
    @csrf @method('POST')
</form>
