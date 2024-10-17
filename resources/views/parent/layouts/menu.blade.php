<div class="side-menu">
    <div class="login-info">
        <img src="{{ !empty(auth()->user()->image) ? 'avatar/' . auth()->user()->image : 'images/man.png' }}" alt="">
        @if(auth()->check())
        <h1>{{ auth()->user()->name }}</h1>
        <p>{{ auth()->user()->username }}</p>
        @else
        <h1>Guest</h1>
        @endif
    </div>
    <ul>
        <!-- <li class="list-title">Configuration</li> -->
        <li class="{{ request()->is('parent') || request()->is('parent/report') ? 'active' : '' }}"><a href="parent/report"><i class="fa-solid fa-file"></i> Report</a></li> 
        <li class="{{ request()->is('parent/permission') ? 'active' : '' }}"><a href="parent/permission"><i class="fa-solid fa-paper-plane"></i> Permission</a></li> 
    </ul>
    </ul>
</div>