import { __ } from '@wordpress/i18n';
import { FormGroup, Input, Label } from '@global';

export const FluentCRM = () => {
	if (lchb_products.fluentcrm_integration === 'true') {
		return (
			<>
				<FormGroup>
					<Label htmlFor="lchb-fluentcrm-lists">
						{__('FluentCRM List IDs', 'licensehub')}
					</Label>
					<Input
						id="lchb-fluentcrm-lists"
						name="lchb-fluentcrm-lists"
						value=""
						helper={__('List IDs separated by comma', 'licensehub')}
					/>
				</FormGroup>
				<FormGroup>
					<Label htmlFor="lchb-fluentcrm-tags">
						{__('FluentCRM Tag IDs', 'licensehub')}
					</Label>
					<Input
						id="lchb-fluentcrm-tags"
						name="lchb-fluentcrm-tags"
						value=""
						helper={__('Tag IDs separated by comma', 'licensehub')}
					/>
				</FormGroup>
			</>
		);
	}
};
