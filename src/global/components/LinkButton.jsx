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
	const defaultClasses =
		'bg-tadaBlue text-white font-kanit border-0 text-xl px-2 py-4 uppercase rounded-lg cursor-pointer transition-all hover:bg-tadaLighterBlue disabled:bg-tadaGray disabled:text-black disabled:cursor-not-allowed';

	return (
		<a
			onClick={click}
			className={className ? defaultClasses + ' ' + className : defaultClasses}>
			{label}
		</a>
	);
};
