import { __ } from '@wordpress/i18n';
import { Table } from '@global/components/table/Table';
import { HeadingTwo } from '@global/components/typography/HeadingTwo';
import { useTables } from '@global/hooks/useTables';

export const ReleaseList = () => {
	const { rows, headers } = useTables(
		window.lchb_releases.releases,
		window.lchb_releases.fields,
	);

	return (
		<>
			<HeadingTwo label={__('Products', 'licensehub')} />
			<Table headers={headers[0]} rows={rows} editable={false} />
		</>
	);
};
