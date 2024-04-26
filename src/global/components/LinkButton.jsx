/**
 * Add an A element stylized like a button
 *
 * @since 1.0.2
 *
 * @param props
 * @returns {JSX.Element}
 * @constructor
 */
export const LinkButton = ({ click, className, label }) => {
	const defaultClasses = 'tada-button';

	return (
		<a
			onClick={click}
			className={className ? defaultClasses + ' ' + className : defaultClasses}>
			{label}
		</a>
	);
};
