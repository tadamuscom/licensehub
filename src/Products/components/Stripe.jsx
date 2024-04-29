import { __ } from '@wordpress/i18n';
import { FormGroup, Input, Label } from '@global';

export const Stripe = ({ result, formData, changeFormValue }) => {
	if (lchb_products.stripe === 'true') {
		return (
			<>
				<FormGroup>
					<Label htmlFor="lchb-stripe-id">
						{__('Stripe Product ID', 'licensehub')}
					</Label>
					<Input
						type="text"
						id="lchb-stripe-id"
						name="lchb-stripe-id"
						value={formData.stripeID}
						result={result}
						onChange={(e) => changeFormValue('stripeID', e.target.value)}
					/>
				</FormGroup>
			</>
		);
	}
};
