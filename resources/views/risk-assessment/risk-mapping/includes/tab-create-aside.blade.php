
<style>
    .nav-tabs.nav-bold.nav-tabs-line {
        display: flex;
		padding-left: 10px;
        flex-direction: column;
    }

    .nav-tabs.nav-bold.nav-tabs-line .nav-item {
        width: 100%;
    }

    .nav-tabs.nav-bold.nav-tabs-line .nav-link {
        text-align: left; /* Adjust the text alignment as needed */
    }
	.nav.nav-tabs.nav-tabs-line .nav-item:first-child .nav-link {
		margin-left: 12px;
	}
</style>
<div class="card card-custom">
	<div class="card-body" style="padding-left:0px; padding-right:0px;">
		<div class="mb-5">
			<ul class="nav nav-tabs nav-bold nav-tabs-line">
				<li class="nav-item">
					<a class="nav-link active" data-toggle="tab" id="riskRegisterTab" href="#first_tab">
						<span class="nav-text">1. Risk Register</span>
					</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" data-toggle="tab" href="#second_tab">
						<span class="nav-text">2. Inherent Risk</span>
					</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" data-toggle="tab" href="#third_tab">
						<span class="nav-text">3. Residual Risk</span>
					</a>
				</li>
				{{-- <li class="nav-item">
					<a class="nav-link" data-toggle="tab" href="#fourth_tab">
						<span class="nav-text">4. Risk Rating</span>
					</a>
				</li> --}}
			</ul>
		</div>
	</div>
</div>
