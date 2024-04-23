import { Button, FormGroup } from '@global';
import { FluentCRM } from '@settings/components/FluentCRM';
import { Stripe } from '@settings/components/Stripe';

export const SettingsForm = ({ onSubmit }) => {
	return (
		<form onSubmit={onSubmit} id="tada-add-product-form">
			<Stripe />
			<FluentCRM />
			<FormGroup extraClass="tada-form-submit">
				<Button label="Save Settings" />
				<p id="tada-status" className="tada-hidden"></p>
			</FormGroup>
		</form>
	);
};
