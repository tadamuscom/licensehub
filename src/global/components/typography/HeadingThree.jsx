/**
 * Add a H3 element
 *
 * @since 1.0.2
 *
 * @param className
 * @param label
 * @param props
 * @returns {JSX.Element}
 * @constructor
 */
export const HeadingThree = ({ className, children, ...props }) => {
	const defaultClasses =
		'tada-bottom-border font-kanit font-bold text-2xl border-b-2 my-4 uppercase w-fit';
	return (
		<h3
			className={className ? defaultClasses + ' ' + className : defaultClasses}
			{...props}>
			{children}
		</h3>
	);
};
