import { FormGroup, Input, Label } from '@global';

export const Stripe = () => {
	if (lchb_products.stripe === 'true') {
		return (
			<>
				<FormGroup>
					<Label htmlFor="lchb-stripe-id" label="Stripe Product ID" />
					<Input id="lchb-stripe-id" name="lchb-stripe-id" value="" />
				</FormGroup>
			</>
		);
	}
};
