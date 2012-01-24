public class C10 {

	public static void main(String[] args) {

		// set q to 5
		int q = 5;

		for (int j = 1; j <= 10; j++) {
			// set n
			int n = 1000000 * j;

			// populate the array
			int[] testArray = new int[n];

			for (int i = 0; i < n; i++) {
				// populate the array so it doesnt have 5 in it
				testArray[i] = i + 11;
				// test the array size
				// System.out.println("test " +testArray[i]);
			}

			// loop the test 5 times to smooth the results

			// Initialise the duration at zero
			double duration = 0;
			// loop 5 times to carry out results smoothing
			for (int i = 0; i < 5; i++) {
				long start = System.currentTimeMillis();
				// call the method
				linearSearch(testArray, n, q);
				// print out the position in the array
				// System.out.print("What location is the value 17 at: ");
				// System.out.println(linearSearch(testArray,n, q));
				long end = System.currentTimeMillis();
				duration += end - start;
			}
			duration = duration / 5;
			System.out.println("Running Time for " + n + " sized array lasts "
					+ duration);
		}
	}

	// method for the algorithm
	public static int linearSearch(int[] A, int n, int q) {
		int index = 0;
		while (index < n && A[index] != q) {
			index = index + 1;
		}
		if (index == n) {
			return -1;
		} else {
			return index;
		}
	}
}
