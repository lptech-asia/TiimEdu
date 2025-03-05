<div class="nav-tabs-custom">
	<ul class="nav nav-tabs pull-right">
		<li class="pull-left header"><i class="fa fa-th"></i> Trường: <strong>{{ university.getName }} - {{ university.getSku }}</strong></li>
		<li class="{{ REQUEST.vs(1) == 'candidate' ? 'active' }}"><a href="{{ MODULE_URL }}candidate/{{ university.getId }}">Ứng viên</a></li>
		<li class="{{ REQUEST.vs(1) == 'program' ? 'active' }}"><a href="{{ MODULE_URL }}program/{{ university.getId }}">Chương trình học</a></li>
		<li class="{{ REQUEST.vs(1) == 'university' ? 'active' }}"><a href="{{ MODULE_URL }}university/{{ university.getId }}">Quản lý Chi tiết</a></li>
	</ul>
	<div class="tab-content">
		<div class="box-under-mobile">
			<div class="row">
				<div class="col-lg-3 col-xs-3 unset-padding">
					<div class="small-box bg-aqua">
						<div class="inner">
							<h3>{{ countProgram }}</i></h3>
							<p>Chương Trình Học</p>
						</div>
						<div class="icon">
							<i class="fa fa-users"></i>
						</div>
						<a href="{{ MODULE_URL }}program/{{ university.getId }}" class="small-box-footer">More info
							<i class="fa fa-arrow-circle-right"></i>
						</a>
					</div>
				</div>
				<div class="col-lg-3 col-xs-3 unset-padding">
					<div class="small-box bg-aqua">
						<div class="inner">
							<h3>{{ countApplication }}</h3>
							<p>Ứng viên</p>
						</div>
						<div class="icon">
							<i class="fa fa-user"></i>
						</div>
						<a href="{{ MODULE_URL }}candidate/{{ university.getId }}" class="small-box-footer">More info
							<i class="fa fa-arrow-circle-right"></i>
						</a>
					</div>
				</div>
				
				<div class="col-lg-3 col-xs-3 unset-padding">
					<ul class="list-group">
					{% for accountant in university.getAccountantEmail|split(';') %}
                        <li class="list-group-item">
                            <b>Accountant Mail {{ loop.index }}</b> <a class="pull-right">{{ accountant }}</a>
                        </li>
					{% endfor %}
                    </ul>
					{# management_email #}
					<ul class="list-group">
					{% for management in university.getManagementEmail|split(';') %}
						<li class="list-group-item">
							<b>Management Mail {{ loop.index }}</b> <a class="pull-right">{{ management }}</a>
						</li>
					{% endfor %}
				</div>

				<div class="col-lg-3 col-xs-3 unset-padding">
					<ul class="list-group">
					{% for enroll in university.getEnrollmentEmail|split(';') %}
                        <li class="list-group-item">
                            <b>Enroll Mail {{ loop.index }}</b> <a class="pull-right">{{ enroll }}</a>
                        </li>
					{% endfor %}
                    </ul>
				</div>
			</div>
		</div>
	</div>
</div>