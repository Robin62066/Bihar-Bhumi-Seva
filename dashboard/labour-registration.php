<?php
include "../config/autoload.php";
if (!is_login()) redirect(base_url('login.php'), 'Please Login to continue', 'danger');
$user_id = user_id();

$distes = $db->select('ai_districts', [],)->result();

$id = $user_id;
$items = $db->select('ai_labours', ['user_id' => $id], 1)->row();
$zones = $db->select('ai_zones')->result();

$user = $db->select('ai_users', ['id' => user_id()], 1)->row();

if (isset($_POST["submit"])) {
	$sb = $_POST['form'];
	$sb["steps"] = 3;
	$db->update('ai_labours', $sb, ['user_id' => $id]);
	redirect(base_url('dashboard/labour-registration.php?id=' . $id), 'successfully save', 'success');
}

if (isset($_POST['update'])) {
	$sb = $_POST['form'];

	$pic_a = do_upload('aadhar_front');
	if ($pic_a != '') $sb['aadhar_front'] = $pic_a;

	$pic_b = do_upload('aadhar_back');
	if ($pic_b != '') $sb['aadhar_back'] = $pic_b;

	$pic_c = do_upload('pan');
	if ($pic_c != '') $sb['pan'] = $pic_c;

	$pic_d = do_upload('passbook');
	if ($pic_d != '') $sb['passbook'] = $pic_d;

	$pic_e = do_upload('photo', true);
	if ($pic_e != '') $sb['photo'] = $pic_e;

	$pic_f = do_upload('voter');
	if ($pic_f != '') $sb['voter'] = $pic_f;
	$sb["steps"] = 4;
	$sb['status'] = 1;
	$db->update('ai_labours', $sb, ['user_id' => $id]);
	redirect(base_url('dashboard/review.php?id=' . $id), 'Check Details Properly.', 'success');
}

if (isset($_POST['submited'])) {
	$sb = $_POST['form'];
	$sb["user_id"] = $id;
	$sb["steps"] = 2;
	$sb['created'] = date("Y-m-d H:i:s");
	$db->insert('ai_labours', $sb);
	redirect(base_url('dashboard/labour-registration.php?id=' . $id), 'successfully save', 'success');
}

include "../common/header.php";
?>
<style>
	.card {
		display: flex;
		justify-content: center;
		margin: 0;
		padding: 0;
		align-items: center;
		border: none;
		width: 100%;
		height: 100%;
	}



	.uname,
	.fname,
	.dob,
	.gender,
	.address,
	.photo {
		position: absolute;
		left: 785px;
		font-weight: 500;
		font-size: 14px;
	}

	.uname {
		bottom: 637px;
		left: 784px;
		width: 150px;
		overflow: hidden;
		text-overflow: clip;
		white-space: nowrap;
	}

	.fname {
		bottom: 602px;
		width: 150px;
		overflow: hidden;
		text-overflow: clip;
		white-space: nowrap;
	}

	.dob {
		bottom: 577px;

	}

	.gender {
		bottom: 551px;

	}

	.address {
		bottom: 529px;
		width: 150px;
		overflow: hidden;
		text-overflow: clip;
		white-space: nowrap;
	}

	.uid {
		position: absolute;
		bottom: 689px;
		left: 697px;
		gap: 4px;
		color: white;
		font-size: 18px;
		font-weight: 800;
	}

	.photo {
		position: absolute;
		bottom: 664px;
		left: 410px;
	}

	.mobile {
		position: absolute;
		bottom: 600px;
		left: 434px;
		font-size: 18px;
		font-weight: 800;
	}

	@media only screen and (max-width: 480px) {
		.save_btn {
			margin-top: -35px;
		}

	}
</style>
<div class="dashboard " id="app">
	<div class="container py-5">
		<div class="user-panel">
			<?php if ($items && $items->steps == 4) { ?>
				<div class="row">
					<?php
					include_once "dashboard-menu.php"; ?>
					<div class="col-sm-9">
						<div class="alert p-3 alert-success ">
							<div class="d-flex justify-content-between align-items-center">
								<span>Labour Registration is Successfully Completed</span>
								<a href="<?= site_url('labours-card.php?id=' . $id)  ?>" class="btn btn-sm btn-primary">Print</a>
							</div>
						</div>

						<div class="container">
							<!-- <div class="card   ">
										<img class="full-bg" src="../image/card1.png" alt="image" height="368" width="552" />
									</div>
									<p class="uname"><?= $items->first_name . " " . $items->middle_name . " " . $items->last_name; ?></p>
									<p class="fname"><?= $items->father_name; ?></p>
									<p class="dob"><?= $items->dob; ?></p>
									<p class="gender"><?= $items->gender; ?></p>
									<p class="mobile"><?= $items->mobile_number; ?></p>
									<p class="address"><?= $items->address1; ?></p>
									<p class="uid" style=" letter-spacing: 8px; "><?= $items->user_id; ?></p>
									<img src="<?= base_url(upload_dir($items->photo)) ?>" class="photo" width="157" height="141" style="border-radius: 10px;" /> -->
						</div>

					</div>
				</div>
			<?php

			} else { ?>
				<div class="faq text-center  ">
					<h3>Labour Registation Form</h3>
				</div>
				<div class="accordion" id="accordionPanelsStayOpenExample">
					<div class="accordion-item ">
						<h2 class="accordion-header">
							<button class="accordion-button" type="button" :class="{ collapsed: activeAccordion !== 1 }"
								aria-expanded="activeAccordion === 1" aria-controls="collapseOne">
								<b>Basic Details :- </b>
							</button>
						</h2>
						<div :id="'collapseOne'" class="accordion-collapse collapse" :class="{ show: activeAccordion === 1 }" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
							<div class="accordion-body">
								<form action="" method="post">
									<div class="row">
										<div class="col-sm-3 col-6 mt-4">
											<label>First Name</label>
											<input type="text" name="form[first_name]" value="<?= $user->first_name; ?>" readonly placeholder="First name" class="form-control bg-light">
										</div>
										<div class="col-sm-3 col-6  mt-4">
											<label>Last Name</label>
											<input type="text" name="form[last_name]" value="<?= $user->last_name; ?>" placeholder="Last name" class="form-control bg-light" readonly>
										</div>
										<div class="col-sm-3 col-6  mt-4">
											<label>Father Name</label>
											<input type="text" name="form[father_name]" maxlength="100" placeholder="Father name" class="form-control" required>
										</div>
										<div class="col-sm-3 col-6  mt-4">
											<label>Mother Name</label>
											<input type="text" name="form[mother_name]" maxlength="100" placeholder="Mother name" class="form-control" required>
										</div>
										<div class="col-sm-3 col-6  mt-4">
											<label>Gender</label>
											<select id="cars" class="form-select" name="form[gender]" required>
												<option value="">Select</option>
												<option value="Male">Male</option>
												<option value="Female">Female</option>
											</select>
										</div>
										<div class="col-sm-3 col-6  mt-4">
											<label>Married Status</label>
											<select id="cars" name="form[marital_status]" class="form-select" required>
												<option value="">Select</option>
												<option value="Married">Married</option>
												<option value="Unmarried">Unmarried</option>
											</select>
										</div>
										<div class="col-sm-3 col-6  mt-4">
											<label>Date of Birth</label>
											<?php
											$maxDob = date("Y-m-d", strtotime("-15 years"));
											?>
											<input type="date" name="form[dob]" max="<?= $maxDob; ?>" placeholder="" class="form-control" required>
										</div>
										<div class="col-sm-3 col-6  mt-4">
											<label>Email ID</label>
											<input type="email" name="form[email_id]" placeholder="Email ID" class="form-control" maxlength="40" required>
										</div>
										<div class="col-sm-3 col-6  mt-4">
											<label>Mobile No.</label>
											<input type="tel" name="form[mobile_number]" placeholder="Mobile No." class="form-control bg-light" value="<?= $user->mobile_number; ?>" maxlength="10" readonly>
										</div>
										<div class="col-sm-3 col-6  mt-4">
											<label>Phone No.2</label>
											<input type="tel" name="form[contact]" maxlength="10" placeholder="Mobile No." class="form-control numeric" required>
										</div>
										<div class="col-sm-3 col-6 mt-5">
											<input type="hidden" name="submited" value="1">
											<button type="submit" class="btn btn-primary btn-submit save_btn">save</button>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
					<div class="accordion-item mt-3">
						<h2 class="accordion-header">
							<button class="accordion-button" type="button" :class="{ collapsed: activeAccordion !== 2 }"
								aria-expanded="activeAccordion === 2" aria-controls="collapseTwo">
								<b class="what">Residential Address :-</b>
							</button>
						</h2>

						<div :id="'collapseTwo'" class="accordion-collapse collapse" :class="{ show: activeAccordion === 2 }" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">

							<div class="accordion-body">
								<form action="" method="post">
									<div class="row">
										<div class="col-6 mt-4">
											<label>District</label>
											<select id="cars" name="form[dist_id]" class="form-select" v-model="dist_id" required @change="set_zones">
												<option value="">Select</option>
												<?php
												foreach ($distes as $dist) {
												?>
													<option value="<?= $dist->id; ?>"><?= $dist->dist_name; ?></option>
												<?php } ?>

											</select>
										</div>
										<div class="col-6 mt-4">
											<label>Anchal</label>
											<select id="cars" name="form[zone_id]" class="form-select" v-model="zone_id" required>
												<option value="">Select</option>
												<option v-for="item in zones" :value="item.id">{{ item.zone_name }}</option>
											</select>
										</div>
										<div class="col-6 mt-4">
											<label>Address</label><br>
											<textarea class="form-control" name="form[address1]" rows="4" required></textarea>
										</div>
										<div class="col-6 mt-4">
											<label>Pin-code</label>
											<input type="text" maxlength="6" name="form[pincode]" class="form-control numeric" required><br>
											<input type="checkbox" id="" name="" value="" checked>
											<label for="vehicle1">You Have a Parmament Address</label>
										</div>
										<div class="col-6 mt-4">
											<input type="hidden" name="submit" value="1">
											<button class="btn btn-primary">Save</button>
										</div>
									</div>
								</form>
							</div>
						</div>

					</div>

					<div class="accordion-item mt-3">
						<h2 class="accordion-header">
							<button class="accordion-button" type="button" :class="{ collapsed: activeAccordion !== 3 }"
								aria-expanded="activeAccordion === 3" aria-controls="collapseThree">
								<b class="what">Upload Documents :-</b>
							</button>
						</h2>
						<div :id="'collapseThree'" class="accordion-collapse collapse" :class="{ show: activeAccordion === 3 }" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
							<div class="accordion-body ">
								<form action="" method="post" enctype="multipart/form-data">
									<div class="row mb-3">
										<div class="col-4 mt-4">
											<label>Profile Photo</label>
											<input type="file" accept="image/*" name="photo" class="form-control" required>

										</div>

										<div class="col-4 mt-4">
											<label>Aadhar Front</label>
											<input type="file" accept="image/*" name="aadhar_front" value="" class="form-control" required>
										</div>
										<div class="col-4 mt-4">
											<label>Aadhar back</label>
											<input type="file" name="aadhar_back" accept="image/*" value="" class="form-control" required>
										</div>
									</div>
									<div class="row ">
										<div>
											<label for="aadhar_no">Aadhar Number</label>
											<input type="text" id="aadhar_no" name="form[aadhar_no]" value="" class="form-control basic numeric" required pattern="\d{12}" placeholder="Enter 12-digit Aadhar Number" maxlength="12" />
										</div>

									</div>

									<div class="col-12 mt-4" align="center">
										<input type="checkbox" id="" name="" value="" checked>
										<label class="mb-3"><a href="<?= site_url('privacy-policy.php'); ?>">Privicy Policy</a> & <a href="<?= site_url('terms-conditions.php'); ?>">Terms and condition</a></label><br>
										<input type="hidden" name="update" value="2">
										<button class="btn btn-primary">Save</button>
										<a href="<?= site_url('dashboard'); ?>" class="btn btn-dark">Cancel</a>

									</div>
								</form>
							</div>
						</div>
					</div>

				</div>

		</div>
	<?php } ?>
	</div>
</div>
</div>
<?php
include "../common/footer.php";
?>
<script>
	new Vue({
		el: '#app',
		data: {
			activeAccordion: <?= (!$items) ? 1 : $items->steps; ?>,
			zones: [],
			dist_id: '',
			zone_id: '',
		},
		methods: {
			toggleAccordion(index) {
				// If the clicked accordion is already active, collapse it
				this.activeAccordion = this.activeAccordion === index ? null : index;
			},
			set_zones: function() {
				this.zones = [];
				api_call('zones', {
					dist_id: this.dist_id
				}).then(resp => {
					if (resp.success) this.zones = resp.data;
				})
			},
		}
	});
</script>