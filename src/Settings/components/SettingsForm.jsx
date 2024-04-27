import apiFetch from '@wordpress/api-fetch';
import { useEffect, useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { Button, FormGroup } from '@global';
import { FormStatus } from '@global/components/form/FormStatus';
import { FluentCRM } from '@settings/components/FluentCRM';
import { Stripe } from '@settings/components/Stripe';

export const SettingsForm = ({ onSubmit }) => {
	const [formValues, setFormValues] = useState({
		stripeIntegration: false,
		stripePublicKey: '',
		stripePrivateKey: '',
		fluentCRMIntegration: false,
	});
	const [loading, setLoading] = useState(false);
	const [status, setStatus] = useState({
		type: '',
		message: '',
		field: '',
	});

	const changeFormValue = (key, value) => {
		setFormValues((prev) => ({
			...prev,
			[key]: value,
		}));
	};

	const setSuccess = (message) => {
		setStatus((prev) => ({
			type: 'success',
			message,
			field: '',
		}));
	};

	const setError = (message, field) => {
		setStatus((prev) => ({
			type: 'error',
			message,
			field: field,
		}));
	};

	useEffect(() => {
		const stripeIntegration = lchb_settings.stripe_integration === 'true';
		const stripePublicKey = lchb_settings.stripe_public_key;
		const stripePrivateKey = lchb_settings.stripe_private_key;
		const stripeFluentCRM = lchb_settings.fluentcrm_integration === 'true';

		changeFormValue('stripeIntegration', stripeIntegration);
		changeFormValue('stripePublicKey', stripePublicKey);
		changeFormValue('stripePrivateKey', stripePrivateKey);
		changeFormValue('fluentCRMIntegration', stripeFluentCRM);
	}, []);

	const handleSubmit = async (e) => {
		e.preventDefault();

		setLoading(true);

		try {
			const res = await apiFetch({
				path: '/tadamus/lchb/v1/general-settings',
				method: 'POST',
				data: JSON.stringify({
					nonce: lchb_settings.nonce,
					...formValues,
				}),
			});

			res.success
				? setSuccess(res.data.message)
				: setError(res.data.message, res.data.field);
		} catch (e) {
			setError(e.message, '');
		} finally {
			setLoading(false);
		}
	};

	return (
		<form onSubmit={handleSubmit}>
			<Stripe
				formValues={formValues}
				changeFormValue={changeFormValue}
				status={status}
			/>
			<FluentCRM
				formValues={formValues}
				changeFormValue={changeFormValue}
				status={status}
			/>
			<FormGroup>
				<Button type="submit" loading={loading}>
					{loading
						? __('Loading...', 'licensehub')
						: __('Save Settings', 'licensehub')}
				</Button>
				<FormStatus status={status} />
			</FormGroup>
		</form>
	);
};
