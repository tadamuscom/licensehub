import apiFetch from '@wordpress/api-fetch';
import { Button, FormGroup, HeadingTwo, Input, Label } from '@global';
import { FluentCRM } from '@products/components/FluentCRM';
import { Stripe } from '@products/components/Stripe';

export const NewProduct = () => {
	const submit = (e) => {
		e.preventDefault();

		const btn = document.querySelector(
			'#' + e.target.id + ' input[type="submit"]',
		);
		btn.disabled = true;
		btn.value = 'Loading...';

		const formData = new FormData(e.target);
		const name = formData.get('lchb-name');
		const stripeID = formData.get('lchb-stripe-id');
		const fluentCRMLists = formData.get('lchb-fluentcrm-lists');
		const fluentCRMTags = formData.get('lchb-fluentcrm-tags');
		const downloadLink = formData.get('lchb-download-link');

		let go = true;

		if (name.length < 1) {
			go = false;
		}

		if (downloadLink.length < 1) {
			go = false;
		}

		const data = {
			nonce: lchb_products.nonce,
			name: name,
			download_link: downloadLink,
		};

		if (stripeID) {
			if (stripeID.length < 1) {
				go = false;
			}

			data.stripe_id = stripeID;
		}

		if (fluentCRMLists) {
			data.fluentcrm_lists = fluentCRMLists;
		}

		if (fluentCRMTags) {
			data.fluentcrm_tags = fluentCRMTags;
		}

		const status = document.getElementById('tada-status');

		if (!go) {
			btn.value = 'Save Product';
			btn.disabled = false;

			status.style.color = 'red';
			status.innerText = 'Please fix the errors above';

			if (status.classList.contains('tada-hidden')) {
				status.classList.remove('tada-hidden');
			}

			return;
		}

		apiFetch({
			path: '/tadamus/lchb/v1/new-product',
			method: 'POST',
			data: data,
		}).then((result) => {
			btn.value = 'Save Product';
			btn.disabled = false;
			status.innerText = result.data.message;

			if (result.success) {
				status.innerText = status.innerText + ' ✅';
				status.style.color = 'green';
				window.location.reload();
			} else {
				status.style.color = 'red';
				status.innerText = status.innerText + ' ❌';
			}

			if (status.classList.contains('tada-hidden')) {
				status.classList.remove('tada-hidden');
			}
		});
	};

	return (
		<div
			style={{
				marginBottom: '15px',
				display: 'none',
			}}
			id="tada-new-product">
			<HeadingTwo label="New Product" />
			<form onSubmit={submit} id="tada-add-product-form">
				<FormGroup>
					<Label htmlFor="lchb-name" label="Name" />
					<Input id="lchb-name" name="lchb-name" value="" />
				</FormGroup>
				<Stripe />
				<FluentCRM />
				<FormGroup>
					<Label htmlFor="lchb-download-link" label="Download Link" />
					<Input id="lchb-download-link" name="lchb-download-link" value="" />
				</FormGroup>
				<FormGroup extraClass="tada-form-submit">
					<Button label="Save Product" />
					<p id="tada-status" className="tada-hidden"></p>
				</FormGroup>
			</form>
		</div>
	);
};
