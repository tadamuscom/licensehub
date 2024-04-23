import { FormGroup, HelperText, Input, Label } from '@global';

export const FluentCRM = () => {
	if (lchb_products.fluentcrm_integration === 'true') {
		return (
			<>
				<FormGroup>
					<Label htmlFor="lchb-fluentcrm-lists" label="FluentCRM List IDs" />
					<Input
						id="lchb-fluentcrm-lists"
						name="lchb-fluentcrm-lists"
						value=""
					/>
					<HelperText content="List IDs separated by comma" />
				</FormGroup>
				<FormGroup>
					<Label htmlFor="lchb-fluentcrm-tags" label="FluentCRM Tag IDs" />
					<Input id="lchb-fluentcrm-tags" name="lchb-fluentcrm-tags" value="" />
					<HelperText content="Tag IDs separated by comma" />
				</FormGroup>
			</>
		);
	}
};
