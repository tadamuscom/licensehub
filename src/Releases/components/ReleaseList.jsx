import { __ } from '@wordpress/i18n';
import { Table, HeadingTwo, useTables } from '@global';

export const ReleaseList = () => {
	const { rows, headers } = useTables(
		window.lchb_releases.releases,
		window.lchb_releases.fields,
	);

	return (
		<>
			<HeadingTwo label={__('Releases', 'licensehub')} />
			<Table
				headers={headers[0]}
				rows={rows}
				editable={false}
				deletable={false}
			/>
		</>
	);
};
