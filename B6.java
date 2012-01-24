public class B6 {

	public static void main(String[] args) {
		// populate the array
		int testArray[] = { 9, 8, 7, 6, 5 };
		// set n
		int n = 5;
		System.out.println("n is :" + n);

		// print out the position in the array
		System.out.print("the lowest value is at location : "
				+ minValueIndex(testArray, n));

	}

	// method to get the minvalue
	public static int minValueIndex(int[] A, int n) {
		int minvalue = 0;
		for (int k = 1; k < n; k++) {
			// print out k
			System.out.println("k is :" + k);
			if (A[minvalue] > A[k]) {
				minvalue = k;
				// print out the location of the lowest number
				System.out.println("minvalue is :" + minvalue);
			}
		}
		return minvalue;
	}

}
