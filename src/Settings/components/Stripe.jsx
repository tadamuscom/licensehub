import { __ } from '@wordpress/i18n';
import { CheckBox, FormGroup, Input, Label } from '@global';

export const Stripe = ({ formValues, changeFormValue, status }) => {
	return (
		<>
			<FormGroup>
				<CheckBox
					label={__('Stripe Integration', 'licensehub')}
					id="tada-stripe-integration"
					name="tada-stripe-integration"
					checked={formValues.stripeIntegration}
					onClick={() =>
						changeFormValue('stripeIntegration', !formValues.stripeIntegration)
					}
				/>
			</FormGroup>
			{formValues.stripeIntegration && (
				<>
					<FormGroup>
						<Label htmlFor="lchb-public-key">
							{__('Public Key', 'licensehub')}
						</Label>
						<Input
							id="lchb-stripe-public-key"
							type="text"
							value={formValues.stripePublicKey}
							status={status}
							onChange={(e) =>
								changeFormValue('stripePublicKey', e.target.value)
							}
						/>
					</FormGroup>

					<FormGroup>
						<Label htmlFor="lchb-private-key">
							{__('Private Key', 'licensehub')}
						</Label>
						<Input
							type="password"
							id="lchb-stripe-private-key"
							value={formValues.stripePrivateKey}
							autoComplete="off"
							status={status}
							onChange={(e) =>
								changeFormValue('stripePrivateKey', e.target.value)
							}
						/>
					</FormGroup>
				</>
			)}
		</>
	);
};
