<div id="rest-cache-admin">
	
	<h1>
		<i class="fas fa-bolt"></i>
		REST Cache
	</h1>
	
	@if ($errors->any())
		<div class="notice notice-error">
			<ul>
				@foreach ($errors->all() as $error)
					<li>{{ $error }}</li>
				@endforeach
			</ul>
		</div>
	@endif
	
	<div id="rest-cache-tabs">
		<ul>
			<li>
				<a href="#records">
					<i class="fas fa-th-list"></i>
					Records
				</a>
			</li>
			<li>
				<a href="#rules">
					<i class="far fa-check-square"></i>
					Rules
				</a>
			</li>
			<li>
				<a href="#settings">
					<i class="fas fa-cogs"></i>
					Settings
				</a>
			</li>
		</ul>
		
		<div id="records">
			<p id="record-controls">
				<button id="refresh-table" class="button button-primary">
					<i class="fas fa-sync"></i>
					Refresh Table
				</button>
				<button id="clear-cache" class="button button-primary">
					<i class="fas fa-trash-alt"></i>
					Clear Cache
				</button>
			</p>
		</div>
		
		<div id="rules">
			<!-- Removed from WP 5.5 - Callback should be __return_true for any public routes -->
			<!-- <div class="notice notice-info">
				<p>
					<i class="fas fa-info-circle"></i>
					<strong>Please note</strong> that this plugin will only cache GET requests. The plugin will not cache <em>any</em> requests which have a permission callback, for security reasons.
				</p>
			</div> -->
		
			<p id="rule-controls">
				<button id="add-rule" class="button button-primary">
					<i class="fas fa-plus"></i>
					Add Rule
				</button>
			</p>
		</div>
		
		<div id="settings">
		
			<form method="POST">
			
				@csrf
			
				<fieldset>
					<legend>
						Record Expiry
					</legend>
					
					<input name="record-expiry-interval-amount" type="number" min="1" step="1" value="1"/>
					<select name="record-expiry-interval-type">
						<option value="SECOND">
							second(s)
						</option>
						
						<option value="MINUTE">
							month(s)
						</option>
						
						<option value="HOUR">
							hour(s)
						</option>
						
						<option value="DAY" selected="selected">
							day(s)
						</option>
						
						<option value="MONTH">
							month(s)
						</option>
						
						<option value="YEAR">
							year(s)
						</option>
					</select>
					
					<label>
						after the record is created
					</label>
				</fieldset>
				
				<button type="submit" class="button button-primary">
					<i class="fas fa-save"></i>
					Save Settings
				</button>
		
			</form>
			
		</div>
		
	</div>
	
</div>