import { __ } from '@wordpress/i18n';
import { Header, HeadingTwo } from '@global';
import { SettingsForm } from '@settings/components/SettingsForm';

export const App = () => {
	return (
		<div className="licensehub-global">
			<Header
				pageTitle={__('Settings', 'licensehub')}
				logoLink={lchb_settings.logo}
			/>
			<HeadingTwo>{__('Settings', 'licensehub')}</HeadingTwo>
			<SettingsForm />
		</div>
	);
};
